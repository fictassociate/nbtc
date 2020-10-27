<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;

class DashboardController extends Controller
{
    public function index(Request $req) {
        if (isset($req->subject_id) && !empty($req->subject_id)) {
            $mast = DB::table('view_dashboard_na')->where('ID_MAST_SUBJECT', $req->subject_id)->where('ID_MAST_DEV', Session::get('plan_id'))->get();
        } else {
            $mast = DB::table('view_dashboard_na')->where('ID_MAST_DEV', Session::get('plan_id'))->orderBy('ID_MAST_INDICATOR')->orderBy('YEAR')->get();
        }
        if (count($mast) > 0) {
            $i = 1;
            $project_code = "";
            foreach ($mast as $m) {
                if ($m->ID_MAST_INDICATOR != $project_code) {
                    $a = 0;
                    $data[$m->ID_MAST_SUBJECT]['ID_MAST_SUBJECT'] = $m->ID_MAST_SUBJECT;
                    $data[$m->ID_MAST_SUBJECT]['SUBJECT_NAME'] = $m->SUBJECT_NAME;
                    $data[$m->ID_MAST_SUBJECT]['GOAL_GROUP'][$m->ID_MAST_GOAL]['ID_MAST_GOAL'] = $m->ID_MAST_GOAL;
                    $data[$m->ID_MAST_SUBJECT]['GOAL_GROUP'][$m->ID_MAST_GOAL]['GOAL_NAME'] = $m->GOAL_NAME;
                    $data[$m->ID_MAST_SUBJECT]['GOAL_GROUP'][$m->ID_MAST_GOAL]['INDICATOR'][$i]['ID_MAST_INDICATOR'] = $m->ID_MAST_INDICATOR;
                    $data[$m->ID_MAST_SUBJECT]['GOAL_GROUP'][$m->ID_MAST_GOAL]['INDICATOR'][$i]['INDICATOR_NAME'] = $m->INDICATOR_NAME;
                    $data[$m->ID_MAST_SUBJECT]['GOAL_GROUP'][$m->ID_MAST_GOAL]['INDICATOR'][$i]['PERCENT'][$a]['YEAR'] = $m->YEAR;
                    $data[$m->ID_MAST_SUBJECT]['GOAL_GROUP'][$m->ID_MAST_GOAL]['INDICATOR'][$i]['PERCENT'][$a]['PERCENT_SUCCESS'] = !empty($m->PERCENT_SUCCESS) ? round($m->PERCENT_SUCCESS) : 0;
                    $c = $i;
                } else {
                    $a++;
                    $data[$m->ID_MAST_SUBJECT]['GOAL_GROUP'][$m->ID_MAST_GOAL]['INDICATOR'][$c]['PERCENT'][$a]['YEAR'] = $m->YEAR;
                    $data[$m->ID_MAST_SUBJECT]['GOAL_GROUP'][$m->ID_MAST_GOAL]['INDICATOR'][$c]['PERCENT'][$a]['PERCENT_SUCCESS'] = !empty($m->PERCENT_SUCCESS) ? round($m->PERCENT_SUCCESS) : 0;
                    $i -= 1;
                }

                $project_code = $m->ID_MAST_INDICATOR;
                $i++;

            }
        } else {
            $i = 1;
            $a = 0;
            $data[0]['ID_MAST_SUBJECT'] = "";
            $data[0]['SUBJECT_NAME'] = "";
            $data[0]['GOAL_GROUP'][0]['ID_MAST_GOAL'] = "";
            $data[0]['GOAL_GROUP'][0]['GOAL_NAME'] = "";
            $data[0]['GOAL_GROUP'][0]['INDICATOR'][$i]['ID_MAST_INDICATOR'] = "";
            $data[0]['GOAL_GROUP'][0]['INDICATOR'][$i]['INDICATOR_NAME'] = "";
            $data[0]['GOAL_GROUP'][0]['INDICATOR'][$i]['PERCENT'][$a]['YEAR'] = "";
            $data[0]['GOAL_GROUP'][0]['INDICATOR'][$i]['PERCENT'][$a]['PERCENT_SUCCESS'] = "";
        }

        // if (isset($data[0]['ID_MAST_SUBJECT']) && !$data[0]['ID_MAST_SUBJECT']) {
        //     return \redirect()->route('subject.index');
        // }

        $subject_score = DB::select('CALL SP_DashBoard_all(' . Session::get('plan_id') . ')');
        $subject = DB::table('mast_subject')->where('ID_MAST_DEV', Session::get('plan_id'))->where('STATUS', 'A')->get();
        $mast = $data;
        // echo "<pre>";
        // print_r($mast);
        // echo "</pre>";
        // exit();
        $data = [
            'mast' => $mast,
            'subject_score' => $subject_score,
            'subject' => $subject,
        ];
        return view('index')->with('data', $data);
    }

    public function project($year, $indicator_id) {
        $mast = DB::select('CALL SP_Project_Chart(' . $year . ', ' . $indicator_id . ')');
        $project_code = "";
        $i = 1;
        foreach ($mast as $m) {
            if ($m->project_code != $project_code) {
                $a = 0;
                $data[$i]['project_code'] = $m->project_code;
                $data[$i]['project_name'] = $m->project_name;
                $data[$i]['department_name'] = $m->department_name;
                $data[$i]['quarter'][$a]['quarter'] = $m->ass_quarter;
                $data[$i]['quarter'][$a]['begin'] = !empty($m->ass_begin) ? $m->ass_begin : 0;
                $data[$i]['quarter'][$a]['mid'] = !empty($m->ass_mid) ? $m->ass_mid : 0;
                $data[$i]['quarter'][$a]['end'] = !empty($m->ass_end) ? $m->ass_end : 0;
            } else {
                $a++;
                $data[$i]['quarter'][$a]['quarter'] = $m->ass_quarter;
                $data[$i]['quarter'][$a]['begin'] = !empty($m->ass_begin) ? $m->ass_begin : 0;
                $data[$i]['quarter'][$a]['mid'] = !empty($m->ass_mid) ? $m->ass_mid : 0;
                $data[$i]['quarter'][$a]['end'] = !empty($m->ass_end) ? $m->ass_end : 0;
                $i++;
            }
            $project_code = $m->project_code;
            $m->project_code != $project_code ? $i++ : '';
        }
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // exit();
        if (!isset($data)) {
            return back()->with('error', 'ไม่มีข้อมูลปี ' . $year);
        }
        $data = [
            'mast' => $data,
        ];

        return view('dashboard.project-chart')->with('data', $data);
    }


    public function show_project(Request $req) {
        $indicator = DB::select("SELECT p.* FROM project_indicator as ind INNER JOIN view_project_list as p ON p.PROJECT_CODE = ind.PROJECT_CODE WHERE ind.ID_MAST_INDICATOR = $req->indicator_id");
        $i = 1;
        $output = "";
        $output .= "<table class='table table-bordered'>";
        $output .= "<tr>";
        $output .= "<th class='text-center'>ลำดับ</th>";
        $output .= "<th>โครงการ</th>";
        $output .= "<th class='text-center'>งบประมาณ</th>";
        $output .= "</tr>";

        foreach ($indicator as $ind) {
            $output .= "<tr>";
            $output .= "<td class='text-center' width='5%'>" . $i . "</td>";
            $output .= "<td width='65%'><a href='/project/edit/$ind->PROJECT_CODE' target='_blank'>" . $ind->PROJECT_NAME . "</a></td>";
            $output .= "<td class='text-right' width='30%'>" . number_format($ind->BUDGET, 2, '.', ',') . "</td>";
            $output .= "</tr>";
            $i++;
        }

        $output .= "</table>";

        echo $output;
    }
}
