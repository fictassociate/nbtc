<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;

class EvalIndicatorController extends Controller
{
    public function index() {
        $mast_year = DB::table('mast_develop_year')->where('ID_MAST_DEV', Session::get('plan_id'))->first();
        $subject = DB::table('mast_subject')->where('ID_MAST_DEV', Session::get('plan_id'))->get();
        $eval_goal = DB::table('mast_goal as g')
        ->join('mast_indicator as in', 'in.ID_MAST_GOAL', 'g.ID_MAST_GOAL')
        ->where('ID_MAST_SUBJECT', $subject[0]->ID_MAST_SUBJECT)
        ->get();
        $view_eval = DB::table('view_national_assessment')->where('YEAR', $mast_year->YEAR)->where('ID_MAST_SUBJECT', $subject[0]->ID_MAST_SUBJECT)->get();
        $eval = count($view_eval) > 0 ? $view_eval : $eval_goal;
        $year = DB::table('mast_develop_year')->where('ID_MAST_DEV', Session::get('plan_id'))->get();


        $data = [
            'subject' => $subject,
            'eval' => $eval,
            'year' => $year,
        ];

        return view('eval_indicator.index')->with('data', $data);
    }

    public function eval(Request $req) {
        $eval = DB::table('mast_goal as g')
        ->join('mast_indicator as in', 'in.ID_MAST_GOAL', 'g.ID_MAST_GOAL')
        ->where('ID_MAST_SUBJECT', $req->get('subject_id'))
        ->get();
        $year = DB::table('mast_develop_year')->where('ID_MAST_DEV', Session::get('plan_id'))->first();

        $view_eval = DB::table('view_national_assessment');
        $view_eval = $view_eval->where('ID_MAST_SUBJECT', $req->get('subject_id'));
        $view_eval = $view_eval->where('YEAR', $req->year);
        $view_eval = $view_eval->get();


        $eval = count($view_eval) > 0 ? $view_eval : $eval;
        $output = "";
        $id = 0;
        foreach ($eval as $row) {
            // $goal_name = $id != $row->ID_MAST_GOAL ? $row->GOAL_NAME : '';
            $goal = isset($row->GOAL) ? $row->GOAL : '';
            $assessment = isset($row->ASSESSMENT) ? $row->ASSESSMENT : '';
            $percent = isset($row->PERCENT_SUCCESS) ? $row->PERCENT_SUCCESS : 0;
            $weight = isset($row->WEIGHT) ? $row->WEIGHT : 0;
            $output .= $id != $row->ID_MAST_GOAL ? "<tr style='background-color: #EBF5FB'><td colspan='5'><h4 style='font-weight: bold;line-height: 1.6em;'>" . $row->GOAL_NAME . "</h4></td></tr>" : "";
            $output .= "<tr>";
            $output .= "<td>" . "<input type='hidden' name='INDICATOR[]' value='" . $row->ID_MAST_INDICATOR . "'><h5 style='font-size: 16px;line-height: 1.6em;'>" . $row->INDICATOR_NAME . "</h5></td>";
            $output .= "<td><input type='number' class='form-control check-number text-right' name='WEIGHT[]' value='$weight' autocomplete='off'></td>";
            $output .= "<td><input type='text' class='form-control' name='GOAL[]' value='$goal' autocomplete='off'></td>";
            $output .= "<td><input type='text' class='form-control' name='ASSESSMENT[]' value='$assessment' autocomplete='off'></td>";
            $output .= "<td><input type='number' class='form-control check-number text-right' name='PERCENT_SUCCESS[]' value='$percent' autocomplete='off'></td>";
            $output .= "</tr>";

            $id = $row->ID_MAST_GOAL;
        }

        echo $output;
    }

    public function update(Request $req, $id) {
        $i = 0;
        foreach ($req->INDICATOR as $indicator) {
            $nation = DB::table('national_assessment')->where('YEAR', $req->YEAR)->where('ID_MAST_INDICATOR', $indicator)->where('ID_MAST_DEV', Session::get('plan_id'))->get();
            if (count($nation) <= 0) {
                DB::table('national_assessment')->insert([
                    'YEAR' => $req->YEAR,
                    'ID_MAST_INDICATOR' => $indicator,
                    'GOAL' => $req->GOAL[$i],
                    'ASSESSMENT' => $req->ASSESSMENT[$i],
                    'PERCENT_SUCCESS' => $req->PERCENT_SUCCESS[$i],
                    'WEIGHT' => $req->WEIGHT[$i],
                    'ID_MAST_DEV' => Session::get('plan_id'),
                ]);
            } else {
                DB::table('national_assessment')->where('YEAR', $req->YEAR)->where('ID_MAST_INDICATOR', $indicator)->where('ID_MAST_DEV', Session::get('plan_id'))->update([
                    'GOAL' => $req->GOAL[$i],
                    'ASSESSMENT' => $req->ASSESSMENT[$i],
                    'PERCENT_SUCCESS' => $req->PERCENT_SUCCESS[$i],
                    'WEIGHT' => $req->WEIGHT[$i],
                ]);
            }

            $i++;
        }

        return back();
    }
}
