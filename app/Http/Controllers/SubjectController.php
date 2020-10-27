<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;

class SubjectController extends Controller
{
    public function index() {
        $MastPlan = DB::table('mast_develop_plan')->where('ID_MAST_DEV', session()->get('plan_id'))->get();
        $MastSubject = DB::table('mast_subject')->where('ID_MAST_DEV', @$MastPlan[0]->ID_MAST_DEV)->where('STATUS', 'A')->orderBy('SUBJECT_NAME', 'ASC')->get();
        $data = [
            'plan' => $MastPlan,
            'subject' => $MastSubject,
        ];

        return view('mast.subject.index')->with('data', $data);
    }

    public function select_plan(Request $req) {
        $MastSubject = DB::table('mast_subject')->where('ID_MAST_DEV', $req->get('plan_id'))->where('STATUS', 'A')->orderBy('ID_MAST_SUBJECT', 'desc')->get();
        $output = "";
        $i = 1;
        foreach ($MastSubject as $row) {
            $output .= "<tr>";
            $output .= "<td>" . $i . "</td>";
            $output .= "<td>" . $row->SUBJECT_NAME . "</td>";
            $output .= "<td class='text-center'> <a href='" . route('subject.edit', ['id'=>$row->ID_MAST_DEV]) . "'><i class='icon-note'></i></a> </td>";
            $output .= "<td class='text-center'> <a href='" . route('subject.destroy', ['id'=>$row->ID_MAST_DEV]) . "' onclick=\"return confirm('ท่านต้องการลบ $row->SUBJECT_NAME ใช่หรือไม่')\"><i class='icon-trash'></i></a> </td>";
            $output .= "</tr>";

            $i++;
        }

        echo $output;
    }

    public function create() {
        $MastDevPlan = DB::table('mast_develop_plan')->orderBy('ID_MAST_DEV', 'desc')->get();

        return view('mast.subject.create')->with('data', $MastDevPlan);
    }

    public function store(Request $req) {
        $sub_name = $req->get('SUBJECT_NAME');
        $id_mast_dev = $req->get('ID_MAST_DEV');
        DB::table('mast_subject')->insert([
            'SUBJECT_NAME' => $sub_name,
            'ID_MAST_DEV' => $id_mast_dev,
            'STATUS' => "A",
        ]);

        return \redirect()->route('subject.index');
    }

    public function edit($id) {
        $subject = DB::table('mast_subject')->where('ID_MAST_SUBJECT', $id)->first();

        return view('mast.subject.edit')->with('data', $subject);
    }

    public function update(Request $req, $id) {
        $subject = DB::table('mast_subject')->where('ID_MAST_SUBJECT', $id)->update([
            'SUBJECT_NAME' => $req->SUBJECT_NAME
        ]);

        return redirect()->route('subject.index')->with('success', 'อัพเดทข้อมูลสำเร็จ');
    }

    public function destroy($id) {
        $subject = DB::table('mast_subject')->where('ID_MAST_SUBJECT', $id);
        $goal = DB::table('mast_goal');
        if ($goal->where('ID_MAST_SUBJECT', $id)->where('STATUS', 'A')->count()) {
            $subject = $subject->first();
            return \redirect()->route('subject.index')->with('error', 'ไม่สามารถลบ ' . $subject->SUBJECT_NAME . ' ได้');
        } else {
            DB::table('mast_subject')->where('ID_MAST_SUBJECT', $id)->update([
                'STATUS'=>'N'
            ]);

            return \redirect()->route('subject.index')->with('success', 'ลบข้อมูลสำเร็จ');
        }
    }
}
