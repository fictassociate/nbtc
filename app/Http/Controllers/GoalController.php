<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;

class GoalController extends Controller
{
    public function index() {
        $plan_id = Session::get('plan_id');

        $MastSubject = DB::table('mast_subject')->where('ID_MAST_DEV', $plan_id)->where('STATUS', 'A')->get();
        $MastSubject_ID = isset($MastSubject[0]->ID_MAST_SUBJECT) ? $MastSubject[0]->ID_MAST_SUBJECT : 0;
        $goal = DB::table('mast_goal')->where('ID_MAST_SUBJECT', $MastSubject_ID)->where('STATUS', 'A')->get();

        $data = [
            'goal' => $goal,
            'MastSubject' => $MastSubject,
        ];

        return view('mast.goal.index')->with('data', $data);
    }

    public function create() {
        $plan = DB::table('mast_develop_plan')->orderBy('ID_MAST_DEV', 'desc')->get();
        $subject = DB::table('mast_subject')->where('ID_MAST_DEV', $plan[0]->ID_MAST_DEV)->get();
        $data = [
            'plan' => $plan,
            'subject' => $subject,
        ];

        return view('mast.goal.create')->with('data', $data);
    }

    public function select_subject(Request $req) {
        $subject = DB::table('mast_subject')->where('ID_MAST_DEV', $req->get('dev_id'))->where('STATUS', 'A')->get();
        $output = "";
        foreach ($subject as $row) {
            $output .= "<option value='" . $row->ID_MAST_SUBJECT . "'>" . $row->SUBJECT_NAME . "</option>";
        }

        echo $output;
    }
    public function select_goal(Request $req) {
        $output = "";
        $subject_id = $req->subject_id;

        $goal = DB::table('mast_goal')->where('ID_MAST_SUBJECT', $subject_id)->where('STATUS', 'A')->get();
        $output .= "<table class='table table-striped table-checkable table-bordered table-hover' id='sample_1'>";
        $output .= "<thead>";
        $output .= "<tr>";
        $output .= "<th width='80%' style='text-align: left;padding-left: 10px'> กลยุทธ์ </th>";
        $output .= "<th width='5%'> แก้ไข </th>";
        $output .= "<th width='5%'> ลบ </th>";
        $output .= "</tr>";
        $output .= "</thead>";
        $output .= "<tbody>";

        foreach ($goal as $row) {
            $edit = "/goal/edit/" . $row->ID_MAST_GOAL;
            $destroy = "/goal/destroy/" . $row->ID_MAST_GOAL;
            $output .= "<tr>";
            $output .= "<td style='text-align: left;padding-left: 10px'>" . $row->GOAL_NAME . "</td>";
            $output .= "<td class='text-center'>" . "<a href='$edit'><i class='icon-note'></i></a>" . "</td>";
            $output .= "<td class='text-center'>" . "<a href='$destroy' onclick=\"return confirm('ท่านต้องการลบ $row->GOAL_NAME ใช่หรือไม่')\"><i class='icon-trash'></i></a>" . "</td>";
            $output .= "</tr>";
        }

        $output .= "</tbody>";
        $output .= "</table>";

        echo $output;
    }

    public function store(Request $req) {
        $goal_name = $req->get('GOAL_NAME');
        $subject_id = $req->get('ID_MAST_SUBJECT');
        DB::table('mast_goal')->insert([
            'GOAL_NAME' => $goal_name,
            'ID_MAST_SUBJECT' => $subject_id,
            'STATUS' => "A",
        ]);

        return \redirect()->route('goal.index');
    }

    public function edit($id) {
        $subject = DB::table('mast_subject')->where('ID_MAST_DEV', Session::get('plan_id'))->get();
        $goal = DB::table('mast_goal')->where('ID_MAST_GOAL', $id)->first();
        $data = [
            'goal' => $goal,
            'subject' => $subject,
        ];

        return view('mast.goal.edit')->with('data', $data);
    }

    public function update(Request $req, $id) {
        $goal = DB::table('mast_goal')->where('ID_MAST_GOAL', $id)->update([
            'GOAL_NAME' => $req->GOAL_NAME,
            'ID_MAST_SUBJECT' => $req->ID_MAST_SUBJECT
        ]);

        return redirect()->route('goal.index')->with('success', 'อัพเดทข้อมูลสำเร็จ');
    }

    public function destroy($id) {
        $goal = DB::table('mast_goal')->where('ID_MAST_GOAL', $id);
        $indicator = DB::table('mast_indicator');
        if ($indicator->where('ID_MAST_GOAL', $id)->where('STATUS', 'A')->count()) {
            $goal = $goal->first();
            return \redirect()->route('goal.index')->with('error', 'ไม่สามารถลบ ' . $goal->GOAL_NAME . ' ได้');
        } else {
            DB::table('mast_goal')->where('ID_MAST_GOAL', $id)->update([
                'STATUS'=>'N'
            ]);

            return \redirect()->route('goal.index')->with('success', 'ลบข้อมูลสำเร็จ');
        }
    }
}
