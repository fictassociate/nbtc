<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;

class DevelopPlanController extends Controller
{
    public function index() {
        $mast_develop_plan = DB::table('mast_develop_plan as p')
        ->orderBy('p.ID_MAST_DEV', 'desc')
        ->get();
        // print_r($Formality);exit();

        return view('mast.develop_plan.index')->with('data', $mast_develop_plan);
    }

    public function create() {
        $mast_develop_plan = DB::table('mast_develop_plan')->orderBy('ID_MAST_DEV', 'desc')->get();

        return view('mast.develop_plan.create')->with('data', $mast_develop_plan);
    }

    public function store(Request $req) {
        $plan_name = $req->get('PLAN_NAME');
        $start_year = $req->get('start_year');
        $end_year = $req->get('end_year');
        $import = $req->get('import');
        $id_mast_dev = $req->get('ID_MAST_DEV');
        DB::table('mast_develop_plan')->insert([
            'PLAN_NAME' => $plan_name,
        ]);
        $lastPlanID = DB::getPdo()->lastInsertId();

        for ($year = $start_year;$year <= $end_year;$year++) {
            DB::table('mast_develop_year')->insert([
                'ID_MAST_DEV' => $lastPlanID,
                'YEAR' => $year,
            ]);
        }

        if ($import != 1 && isset($req->import)) {
            $subjects = DB::table('mast_subject')->where('ID_MAST_DEV', $id_mast_dev)->where('STATUS', 'A')->get();
            foreach ($subjects as $subject) {
                DB::table('mast_subject')->insert([
                    'SUBJECT_NAME' => $subject->SUBJECT_NAME,
                    'ID_MAST_DEV' => $lastPlanID,
                    'STATUS' => "A",
                ]);
                $lastSubjectID = DB::getPdo()->lastInsertId();

                $goals = DB::table('mast_goal')->where('ID_MAST_SUBJECT', $subject->ID_MAST_SUBJECT)->where('STATUS', 'A')->get();
                foreach ($goals as $goal) {
                    DB::table('mast_goal')->insert([
                        'GOAL_NAME' => $goal->GOAL_NAME,
                        'ID_MAST_SUBJECT' => $lastSubjectID,
                        'STATUS' => "A",
                    ]);
                    $lastGoalID = DB::getPdo()->lastInsertId();

                    $indicators = DB::table('mast_indicator')->where('ID_MAST_GOAL', $goal->ID_MAST_GOAL)->where('STATUS', 'A')->get();
                    foreach ($indicators as $indicator) {
                        DB::table('mast_indicator')->insert([
                            'INDICATOR_NAME' => $indicator->INDICATOR_NAME,
                            'ID_MAST_GOAL' => $lastGoalID,
                            'STATUS' => "A",
                        ]);
                    }
                }
            }
        }
        $MastPlan = DB::table('mast_develop_plan')->orderBy('ID_MAST_DEV', 'desc')->get();
        Session::put('plan', $MastPlan);


        return redirect()->route('develop_plan.index')->with('success', 'เพิ่ม ' . $plan_name . ' สำเร็จ');
        // exit();
    }

    public function edit($id) {
        $plan = DB::table('view_plan');
        $plan = $plan->where('ID_MAST_DEV', $id)->first();

        return view('mast.develop_plan.edit')->with('plan', $plan);
    }

    public function update(Request $req, $id) {
        $plan_name = $req->get('PLAN_NAME');
        $start_year = $req->get('start_year');
        $end_year = $req->get('end_year');
        DB::table('mast_develop_plan')->where('ID_MAST_DEV', $id)->update([
            'PLAN_NAME' => $plan_name,
        ]);

        DB::table('mast_develop_year')->where('ID_MAST_DEV', $id)->delete();

        for ($year = $start_year;$year <= $end_year;$year++) {
            DB::table('mast_develop_year')->insert([
                'ID_MAST_DEV' => $id,
                'YEAR' => $year,
            ]);
        }

        return redirect()->route('develop_plan.index')->with('success', 'อัพเดต ' . $plan_name . ' สำเร็จ');
    }

    public function destroy($id) {
        $plan = DB::table('mast_develop_plan');
        $year = DB::table('mast_develop_year');
        $subject = DB::table('mast_subject');
        if ($subject->where('ID_MAST_DEV', $id)->count() > 0) {
            $data = $plan->where('ID_MAST_DEV', $id)->first();
            return \redirect()->route('develop_plan.index')->with('error', 'ไม่สามารถลบ ' . $data->PLAN_NAME . ' ได้');
        } else {
            $data = $plan->where('ID_MAST_DEV', $id)->first();
            $year->where('ID_MAST_DEV', $id)->delete();
            $plan->where('ID_MAST_DEV', $id)->delete();

            $MastPlan = DB::table('mast_develop_plan')->orderBy('ID_MAST_DEV', 'desc')->get();
            Session::put('plan', $MastPlan);

            return \redirect()->route('develop_plan.index')->with('success', 'ลบ  ' . $data->PLAN_NAME . ' สำเร็จ');
        }
    }
}
