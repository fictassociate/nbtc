<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;

class IndicatorController extends Controller
{
    public function index() {
        $MastPlan = DB::table('mast_develop_plan')->where('ID_MAST_DEV', Session::get('plan_id'))->get();
        $MastSubject = DB::table('mast_subject')->where('ID_MAST_DEV', @$MastPlan[0]->ID_MAST_DEV)->where('STATUS', 'A')->get();
        $MastSubject_ID = isset($MastSubject[0]->ID_MAST_SUBJECT) ? @$MastSubject[0]->ID_MAST_SUBJECT : 0;
        $MastGoal = DB::table('mast_goal')->where('ID_MAST_SUBJECT', $MastSubject_ID)->where('STATUS', 'A')->get();
        $MastGoal_ID = isset($MastGoal[0]->ID_MAST_GOAL) ? @$MastGoal[0]->ID_MAST_GOAL : 0;
        $MastIndicator = DB::table('mast_indicator')->where('ID_MAST_GOAL', $MastGoal_ID)->where('STATUS', 'A')->get();
        // print_r($MastSubject_ID);exit();

        $data = [
            'MastPlan' => $MastPlan,
            'MastSubject' => $MastSubject,
            'MastGoal' => $MastGoal,
            'MastIndicator' => $MastIndicator,
        ];

        return view('mast.indicator.index')->with('data', $data);
    }

    public function create() {
        $plan = DB::table('mast_develop_plan')->orderBy('ID_MAST_DEV', 'desc')->get();
        $subject = DB::table('mast_subject')->where('ID_MAST_DEV', $plan[0]->ID_MAST_DEV)->get();
        $subject_id = isset($subject[0]->ID_MAST_SUBJECT) ? $subject[0]->ID_MAST_SUBJECT : 0;
        $goal = DB::table('mast_goal')->where('ID_MAST_SUBJECT', $subject_id)->get();
        $data = [
            'plan' => $plan,
            'subject' => $subject,
            'goal' => $goal,
        ];

        return view('mast.indicator.create')->with('data', $data);
    }

    public function select_subject(Request $req) {
        $subject = DB::table('mast_subject')->where('ID_MAST_DEV', $req->get('dev_id'))->get();
        $output = "";
        foreach ($subject as $row) {
            $output .= "<option value='" . $row->ID_MAST_SUBJECT . "'>" . $row->SUBJECT_NAME . "</option>";
        }

        echo $output;
    }
    public function select_goal(Request $req) {
        if (isset($_GET['dev_id'])) {
            $subject = DB::table('mast_subject')->where('ID_MAST_DEV', $req->get('dev_id'))->get();
            $subject_id = isset($subject[0]->ID_MAST_SUBJECT) ? $subject[0]->ID_MAST_SUBJECT : 0;
        } else {
            $subject_id = $req->get('subject_id');
        }
        $goal = DB::table('mast_goal')->where('ID_MAST_SUBJECT', $subject_id)->get();
        $output = "";
        foreach ($goal as $row) {
            $output .= "<option value='" . $row->ID_MAST_GOAL . "'>" . $row->GOAL_NAME . "</option>";
        }

        echo $output;
    }

    public function data_indicator(Request $req) {
        $MastPlan = DB::table('mast_develop_plan');
        if (isset($_GET['dev_id'])) {
            $MastPlan = $MastPlan->where('ID_MAST_DEV', $req->get('dev_id'));
        }
        $MastPlan = $MastPlan->orderBy('ID_MAST_DEV', 'desc')->first();

        $MastSubject = DB::table('mast_subject')->where('ID_MAST_DEV', $MastPlan->ID_MAST_DEV)->first();
        $MastSubject_ID = isset($MastSubject->ID_MAST_SUBJECT) ? $MastSubject->ID_MAST_SUBJECT : 0;

        $MastGoal = DB::table('mast_goal');
        if (isset($_GET['subject_id'])) {
            $MastGoal = $MastGoal->where('ID_MAST_SUBJECT', $req->get('subject_id'));
        } else {
            $MastGoal = $MastGoal->where('ID_MAST_SUBJECT', $MastSubject_ID);
        }
        $MastGoal = $MastGoal->first();

        $MastGoal_ID = isset($MastGoal->ID_MAST_GOAL) ? $MastGoal->ID_MAST_GOAL : 0;
        $MastIndicator = DB::table('mast_indicator')->where('STATUS', 'A')->where('ID_MAST_GOAL', isset($_GET['goal_id']) ? $req->get('goal_id') : $MastGoal_ID)->get();
        $output = "";
        foreach ($MastIndicator as $row) {
            $output .= "<tr>";
            $output .= "<td style='text-align: left;padding-left: 10px'>" . $row->INDICATOR_NAME . "</td>";
            $output .= "<td class='text-center'> <a href='" . route('indicator.edit', ['id'=>$row->ID_MAST_INDICATOR]) . "'><i class='icon-note'></i></a> </td>";
            $output .= "<td class='text-center'> <a href='" . route('indicator.destroy', ['id'=>$row->ID_MAST_INDICATOR]) . "' onclick=\"return confirm('ท่านต้องการลบ $row->INDICATOR_NAME ใช่หรือไม่')\"><i class='icon-trash'></i></a> </td>";
            $output .= "</tr>";
        }

        echo $output;
    }

    public function store(Request $req) {
        $ind_name = $req->get('INDICATOR_NAME');
        $goal_id = $req->get('ID_MAST_GOAL');
        DB::table('mast_indicator')->insert([
            'INDICATOR_NAME' => $ind_name,
            'ID_MAST_GOAL' => $goal_id,
            'STATUS' => 'A',
        ]);

        return \redirect()->route('indicator.index')->with('success', 'เพิ่ม ' . $ind_name . ' สำเร็จ');
    }

    public function edit($id) {
        $indicator = DB::table('mast_indicator')->where('ID_MAST_INDICATOR', $id)->first();
        $goal_first = DB::table('mast_goal')->where('ID_MAST_GOAL', $indicator->ID_MAST_GOAL)->first();
        $subject_id = DB::table('mast_subject')->where('ID_MAST_SUBJECT', $goal_first->ID_MAST_SUBJECT)->first();
        $subject = DB::table('mast_subject')->where('ID_MAST_DEV', Session::get('plan_id'))->get();
        $goal = DB::table('mast_goal')->where('ID_MAST_SUBJECT', $goal_first->ID_MAST_SUBJECT)->get();
        $data = [
            'subject' => $subject,
            'goal' => $goal,
            'indicator' => $indicator,
            'subject_id' => $subject_id,
        ];

        return view('mast.indicator.edit')->with('data', $data);
    }

    public function update(Request $req, $id) {
        $subject = DB::table('mast_indicator')->where('ID_MAST_INDICATOR', $id)->update([
            'INDICATOR_NAME' => $req->INDICATOR_NAME,
            'ID_MAST_GOAL' => $req->ID_MAST_GOAL
        ]);

        return redirect()->route('indicator.index')->with('success', 'อัพเดทข้อมูลสำเร็จ');
    }

    public function destroy($id) {
        $indicator = DB::table('mast_indicator');
        $data = $indicator->where('ID_MAST_INDICATOR', $id)->first();
        $indicator->where('ID_MAST_INDICATOR', $id)->update([
            'STATUS' => 'N'
        ]);

        return \redirect()->route('indicator.index')->with('success', 'ลบ  ' . $data->INDICATOR_NAME . ' สำเร็จ');
    }
}
