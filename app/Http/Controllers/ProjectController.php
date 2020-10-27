<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use DB;

class ProjectController extends Controller
{
    public function index() {
        $Project = DB::table('view_project_list')->where('DEV_ID', Session::get('plan_id'))->get();
        $subject = DB::table('mast_subject')->get();
        $data = [
            'subject' => $subject,
            'project' => $Project,
        ];

        return view('project.index')->with('data', $data);
    }

    public function create() {
        $MastPlan = DB::table('mast_develop_plan')->where('ID_MAST_DEV', Session::get('plan_id'))->where('STATUS', 'A')->get();
        $MastSubject = DB::table('mast_subject')->where('STATUS', 'A')->get();
        $MastGoal = DB::table('mast_goal')->where('STATUS', 'A')->get();
        $MastIndicator = DB::table('mast_indicator')->where('STATUS', 'A')->get();
        if (!count($MastSubject)) { return redirect()->route('subject.index'); }
        else if (!count($MastGoal)) { return redirect()->route('goal.index'); }
        else if (!count($MastIndicator)) { return redirect()->route('indicator.index'); }
        $MastYear = DB::table('mast_develop_year')->where('ID_MAST_DEV', Session::get('plan_id'))->get();
        $Province = DB::table('province')->get();
        $department = DB::table('department')->get();
        $data = [
            'MastPlan' => $MastPlan,
            'MastSubject' => $MastSubject,
            'MastGoal' => $MastGoal,
            'MastIndicator' => $MastIndicator,
            'MastYear' => $MastYear,
            'Province' => $Province,
            'department' => $department,
        ];

        return view('project.create')->with('data' , $data);
    }

    public function store(Request $req) {
        $project_code = $req->get('PROJECT_CODE');
        $project_name = $req->get('PROJECT_NAME');
        $object = $req->get('OBJECTIVE');
        $province_code = $req->get('PROVINCE_CODE');
        $start = explode('/', $req->get('DT_START'));
        $dt_start = date('Y-m-d', strtotime(@$start['2'] . '-' . @$start['1'] . '-' . @$start['0']));
        $end = explode('/', $req->get('DT_END'));
        $dt_end = date('Y-m-d', strtotime(@$end['2'] . '-' . @$end['1'] . '-' . @$end['0']));

        if (Session::get('role') == "S") {
            $user = DB::table('user')->where('DEPARTMENT_CODE', $req->DEPARTMENT)->first();
            $username = $user->USER_LOGIN;
        } else {
            $username = Session::get('username');
        }

        $Project = DB::table('project')->insert([
            'PROJECT_CODE' => $project_code,
            'PROJECT_NAME' => $project_name,
            'SUMMARY_PROCESS' => $req->SUMMARY_PROCESS,
            'DT_START' => $dt_start,
            'DT_END' => $dt_end,
            'USER_LOGIN' => $username,
        ]);

        $MastYear = $req->YEAR;
        $i = 0;
        foreach ($MastYear as $year) {
            DB::table('project_budget')->insert([
                'PROJECT_CODE' => $project_code,
                'BUDGET_YEAR' => $year,
                'BUDGET' => $req->get('BUDGET')[$i] != "" ? $req->get('BUDGET')[$i] : 0,
                'BUDGET2' => $req->get('BUDGET2')[$i] != "" ? $req->get('BUDGET2')[$i] : 0,
                'DT_CREATED' => date('Y-m-d'),
                'USER_LOGIN' => $username,
            ]);
            $i++;
        }

        foreach ($req->get('ID_MAST_INDICATOR') as $indicator_id) {
            DB::table('project_indicator')->insert([
                'PROJECT_CODE' => $project_code,
                'ID_MAST_INDICATOR' => $indicator_id,
                'DT_CREATED' => date('Y-m-d'),
                'USER_LOGIN' => $username,
            ]);
        }

        return \redirect()->route('project.index')->with('success', 'เพิ่มโครงการ' . $project_name . 'สำเร็จ');
    }

    public function edit($id) {
        if (Session::get('result_id') && Session::get('result_id') != $id) {
            Session::forget('result_id');
            Session::forget('outcome');
        }
        $MastPlan = DB::table('mast_develop_plan')->where('ID_MAST_DEV', Session::get('plan_id'))->where('STATUS', 'A')->get();
        $MastSubject = DB::table('mast_subject')->where('STATUS', 'A')->get();
        $MastGoal = DB::table('mast_goal')->where('STATUS', 'A')->get();
        $MastIndicator = DB::table('mast_indicator')->where('STATUS', 'A')->get();
        $MastYear = DB::table('mast_develop_year')->where('ID_MAST_DEV', Session::get('plan_id'))->get();
        $Province = DB::table('province')->get();
        $Project = DB::table('project')->where('PROJECT_CODE', $id)->first();
        $result = DB::table('view_result')->where('PROJECT_CODE', $id)->select('ID_MAST_DEV', 'ID_MAST_SUBJECT', 'ID_MAST_GOAL', 'ID_MAST_INDICATOR')->get();
        $budget = DB::table('project_budget')->where('PROJECT_CODE', $id)->get();
        $begin = DB::table('view_result_begin')->where('PROJECT_CODE', $id)->get();
        $mid = DB::table('view_result_mid')->where('PROJECT_CODE', $id)->get();
        $end = DB::table('view_result_end')->where('PROJECT_CODE', $id)->get();

        if (count($begin) > 0) {
            $i = 1;
            $begin_id = "";
            $year = "";
            $quarter = "";
            foreach ($begin as $b) {
                if ($begin_id != $b->ID_PROJECT_BEGIN_RESULT || $b->YEAR != $year || $b->QUARTER != $quarter) {
                    $a = 0;
                    $result_begin[$i]['ID_PROJECT_BEGIN_RESULT'] =$b->ID_PROJECT_BEGIN_RESULT;
                    $result_begin[$i]['PROJECT_CODE'] =$b->PROJECT_CODE;
                    $result_begin[$i]['QUARTER'] =$b->QUARTER;
                    $result_begin[$i]['YEAR'] =$b->YEAR;
                    $result_begin[$i]['BEGIN_RESULT'] =$b->BEGIN_RESULT;
                    $result_begin[$i]['GOAL'] =$b->GOAL;
                    $result_begin[$i]['ACTUAL'] =$b->ACTUAL;
                    $result_begin[$i]['INDICATOR_NAME'][$a] =$b->INDICATOR_NAME;
                } else {
                    $i = $i - 1;
                    $a++;
                    $result_begin[$i]['INDICATOR_NAME'][$a] =$b->INDICATOR_NAME;
                }

                $i++;
                $begin_id = $b->ID_PROJECT_BEGIN_RESULT;
                $year = $b->YEAR;
                $quarter = $b->QUARTER;
            }
        } else {
            $begin = "";
        }

        if (count($mid) > 0) {
            $i = 1;
            $mid_id = "";
            $year = "";
            $quarter = "";
            foreach ($mid as $b) {
                if ($mid_id != $b->ID_PROJECT_MID_RESULT || $b->YEAR != $year || $b->QUARTER != $quarter) {
                    $a = 0;
                    $result_mid[$i]['ID_PROJECT_MID_RESULT'] =$b->ID_PROJECT_MID_RESULT;
                    $result_mid[$i]['PROJECT_CODE'] =$b->PROJECT_CODE;
                    $result_mid[$i]['QUARTER'] =$b->QUARTER;
                    $result_mid[$i]['YEAR'] =$b->YEAR;
                    $result_mid[$i]['MID_RESULT'] =$b->MID_RESULT;
                    $result_mid[$i]['GOAL'] =$b->GOAL;
                    $result_mid[$i]['ACTUAL'] =$b->ACTUAL;
                    $result_mid[$i]['INDICATOR_NAME'][$a] =$b->INDICATOR_NAME;
                } else {
                    $i = $i - 1;
                    $a++;
                    $result_mid[$i]['INDICATOR_NAME'][$a] =$b->INDICATOR_NAME;
                }

                $i++;
                $mid_id = $b->ID_PROJECT_MID_RESULT;
                $year = $b->YEAR;
                $quarter = $b->QUARTER;
            }
        } else {
            $mid = "";
        }

        if (count($end) > 0) {
            $i = 1;
            $end_id = "";
            $year = "";
            $quarter = "";
            foreach ($end as $b) {
                if ($end_id != $b->ID_PROJECT_END_RESULT || $b->YEAR != $year || $b->QUARTER != $quarter) {
                    $a = 0;
                    $result_end[$i]['ID_PROJECT_END_RESULT'] =$b->ID_PROJECT_END_RESULT;
                    $result_end[$i]['PROJECT_CODE'] =$b->PROJECT_CODE;
                    $result_end[$i]['QUARTER'] =$b->QUARTER;
                    $result_end[$i]['YEAR'] =$b->YEAR;
                    $result_end[$i]['END_RESULT'] =$b->END_RESULT;
                    $result_end[$i]['GOAL'] =$b->GOAL;
                    $result_end[$i]['ACTUAL'] =$b->ACTUAL;
                    $result_end[$i]['INDICATOR_NAME'][$a] =$b->INDICATOR_NAME;
                } else {
                    $i = $i - 1;
                    $a++;
                    $result_end[$i]['INDICATOR_NAME'][$a] =$b->INDICATOR_NAME;
                }

                $i++;
                $end_id = $b->ID_PROJECT_END_RESULT;
                $year = $b->YEAR;
                $quarter = $b->QUARTER;
            }
        } else {
            $end = "";
        }

        $json = json_decode($result);
        foreach ($json as $row) {
            $resultList['ID_MAST_GOAL'][] = $row->ID_MAST_GOAL;
            $resultList['ID_MAST_SUBJECT'][] = $row->ID_MAST_SUBJECT;
            $resultList['ID_MAST_INDICATOR'][] = $row->ID_MAST_INDICATOR;
        }

        if (!isset($resultList)) {
            $resultList['ID_MAST_GOAL'][] = '';
            $resultList['ID_MAST_SUBJECT'][] = '';
            $resultList['ID_MAST_INDICATOR'][] = '';
        }

        $user = Collect(DB::select('SELECT u.DEPARTMENT_CODE FROM project p INNER JOIN `user` u ON u.USER_LOGIN = p.USER_LOGIN'))->first();
        $department_code = $user->DEPARTMENT_CODE;
        $department = DB::table('department')->get();

        // status
        $status1 = DB::table('project_status')->where('PROJECT_CODE', $id)->where('STATUS', 1)->first();
        $status2 = DB::table('project_status')->where('PROJECT_CODE', $id)->where('STATUS', 2)->first();
        $status3 = DB::table('project_status')->where('PROJECT_CODE', $id)->where('STATUS', 3)->first();
        $status4 = DB::table('project_status')->where('PROJECT_CODE', $id)->where('STATUS', 4)->first();
        $data = [
            'MastPlan' => $MastPlan,
            'MastSubject' => $MastSubject,
            'MastGoal' => $MastGoal,
            'MastIndicator' => $MastIndicator,
            'MastYear' => $MastYear,
            'Province' => $Province,
            'project' => $Project,
            'result' => $resultList,
            'budget' => $budget,
            'begin' => @$result_begin,
            'mid' => @$result_mid,
            'end' => @$result_end,
            'department' => @$department,
            'department_code' => @$department_code,
            'status1' => @$status1,
            'status2' => @$status2,
            'status3' => @$status3,
            'status4' => @$status4,
        ];

        return view('project.edit')->with('data' , $data);
    }

    public function update(Request $req, $id) {
        $project = DB::table('project')->where('PROJECT_CODE', $id)->first();

        $project_code = $req->get('PROJECT_CODE');
        $project_name = $req->get('PROJECT_NAME');
        $object = $req->get('OBJECTIVE');
        $province_code = $req->get('PROVINCE_CODE');
        $start = explode('/', $req->get('DT_START'));
        $dt_start = date('Y-m-d', strtotime($start['2'] . '-' . $start['1'] . '-' . $start['0']));
        $end = explode('/', $req->get('DT_END'));
        $dt_end = date('Y-m-d', strtotime($end['2'] . '-' . $end['1'] . '-' . $end['0']));

        $Project = DB::table('project')->where('PROJECT_CODE', $id)->update([
            'PROJECT_NAME' => $project_name,
            'OBJECTIVE' => $object,
            'PROVINCE_CODE' => $province_code,
            'PROBLEM' => $req->PROBLEM,
            'SUGGESTION' => $req->SUGGESTION,
            'USER_LOGIN' => $req->DEPARTMENT . "01",
            'SUMMARY_PROCESS' => $req->SUMMARY_PROCESS,
            'DT_START' => $dt_start,
            'DT_END' => $dt_end,
        ]);

        for ($i = 0;$i < 4;$i++) {
          DB::table('project_status')
            ->where('PROJECT_CODE', $project_code)
            ->where('STATUS', $i + 1)
            ->delete();
          if (isset($req->project_status[$i])) {
            if ($req->project_status[$i] < 4) {
              $dex = explode('/', $req->statusDate[$i]);
              $statusDate = date('Y-m-d', strtotime($dex[2] . "-" . $dex[1] . "-" . $dex[0]));
            }
            else {
              $statusDate = NULL;
            }

            if ($req->project_status[$i] == 2) {
              $detail = json_encode(
                [
                  "text21" => $req->text21,
                  "text22" => [
                    "status" => $req->sec_2_2,
                    "value" => $req->sec_2_2 != 1
                              ?
                              ($req->sec_2_2 == 2 ? $req->text22 : $req->text23)
                              :
                              NULL
                  ],
                  "text24" => $req->text24,
                  "text25" => [
                    "status" => $req->sec_2_4,
                    "value" => $req->sec_2_4 != 1
                              ?
                              ($req->sec_2_4 == 2 ? $req->text25 : $req->text26)
                              :
                              NULL
                  ],
                ]
              );
            }
            else if ($req->project_status[$i] == 4) {
              $detail = json_encode([
                "detail" => $req->text41
              ]);
            }
            else {
              $detail = NULL;
            }

            // Check true status in database
            DB::table('project_status')->insert([
              "PROJECT_CODE" => $project_code,
              "STATUS" => $req->project_status[$i],
              "DETAIL" => $detail,
              "CREATED_AT" => $statusDate
            ]);
          }
        }

        if (Session::get('role') == "S") {
            $username = $project->USER_LOGIN;
        } else {
            $username = Session::get('username');
        }

        DB::table('project_budget')->where('PROJECT_CODE', $id)->delete();
        DB::table('project_indicator')->where('PROJECT_CODE', $id)->delete();

        $MastYear = $req->YEAR;
        $i = 0;
        foreach ($MastYear as $year) {
            DB::table('project_budget')->insert([
                'PROJECT_CODE' => $project_code,
                'BUDGET_YEAR' => $year,
                'BUDGET2' => $req->get('BUDGET2')[$i] != "" ? $req->get('BUDGET2')[$i] : 0,
                'BUDGET' => $req->get('BUDGET')[$i] != "" ? $req->get('BUDGET')[$i] : 0,
                'DT_CREATED' => date('Y-m-d'),
                'USER_LOGIN' => $username,
            ]);
            $i++;
        }

        foreach ($req->get('ID_MAST_INDICATOR') as $indicator_id) {
            DB::table('project_indicator')->insert([
                'PROJECT_CODE' => $project_code,
                'ID_MAST_INDICATOR' => $indicator_id,
                'DT_CREATED' => date('Y-m-d'),
                'USER_LOGIN' => $username,
            ]);
        }

        return \redirect()->route('project.index')->with('success', 'แก้ไขโครงการ' . $project_name . 'สำเร็จ');
    }

    public function result($id) {
        $project = DB::table('project')->where('PROJECT_CODE', $id)->select('OUTCOME')->first();
        $Result = DB::table('view_result')->where('PROJECT_CODE', $id)->get();
        $MastYear = DB::table('mast_develop_year as y')
        ->join('mast_develop_plan as p', 'p.ID_MAST_DEV', 'y.ID_MAST_DEV')
        ->join('mast_subject as s', 's.ID_MAST_DEV', 'p.ID_MAST_DEV')
        ->join('mast_goal as g', 's.ID_MAST_SUBJECT', 'g.ID_MAST_SUBJECT')
        ->join('mast_indicator as i', 'i.ID_MAST_GOAL', 'g.ID_MAST_GOAL')
        ->join('project_indicator as pi', 'pi.ID_MAST_INDICATOR', 'i.ID_MAST_INDICATOR')
        ->where('pi.PROJECT_CODE', $id)
        ->groupBy('y.YEAR')
        ->select('y.YEAR')
        ->get();
        $begin_result = DB::table('project_begin_result')->where('PROJECT_CODE', $id)->first();
        $mid_result = DB::table('project_mid_result')->where('PROJECT_CODE', $id)->first();
        $end_result = DB::table('project_end_result')->where('PROJECT_CODE', $id)->first();
        $begin_indicator = DB::table('project_begin_indicator')->where('ID_PROJECT_BEGIN_RESULT', @$begin_result->ID_PROJECT_BEGIN_RESULT)->get();
        $mid_indicator = DB::table('project_mid_indicator')->where('ID_PROJECT_MID_RESULT', @$mid_result->ID_PROJECT_MID_RESULT)->get();
        $end_indicator = DB::table('project_end_indicator')->where('ID_PROJECT_END_RESULT', @$end_result->ID_PROJECT_END_RESULT)->get();

        $data = [
            'Result' => $Result,
            'MastYear' => $MastYear,
            'project' => $project,
            'begin_result' => $begin_result,
            'mid_result' => $mid_result,
            'end_result' => $end_result,
            'begin_indicator' => $begin_indicator,
            'mid_indicator' => $mid_indicator,
            'end_indicator' => $end_indicator,
        ];

        return view('project.result')->with('data', $data);
    }

    public function result_store(Request $req, $id) {
        for ($i = 1;$i <4;$i++) {
            if ($req->RESULT[$i] != '' && !empty($req->RESULT[$i])) {
                $data[$i]['QUARTER'] = $req->QUARTER[$i];
                $data[$i]['YEAR'] = $req->YEAR[$i];
                $data[$i]['RESULT'] = $req->RESULT[$i];
                $data[$i]['INDICATOR'] = $req->INDICATOR[$i];
                $data[$i]['GOAL'] = $req->GOAL[$i];
                $data[$i]['ACTUAL'] = $req->ACTUAL[$i];
            } else {
                continue;
            }
        }
        $Project = DB::table('project')->where('PROJECT_CODE', $id)->update([
            'OUTCOME' => $req->OUTCOME,
        ]);


        for ($i = 1;$i < 4;$i++) {
            if (isset($data[$i]['RESULT'])) {
               switch ($i) {
                   case 1 :
                    $table_result = "project_begin_result";
                    $table_indicator = "project_begin_indicator";
                    $result = "BEGIN";
                    $result_indicator_id = 'ID_PROJECT_' . $result . '_RESULT';
                   break;
                   case 2 :
                    $table_result = "project_mid_result";
                    $table_indicator = "project_mid_indicator";
                    $result = "MID";
                    $result_indicator_id = 'ID_PROJECT_' . $result . '_RESULT';
                    break;
                   case 3 :
                    $table_result = "project_end_result";
                    $table_indicator = "project_end_indicator";
                    $result = "END";
                    $result_indicator_id = 'ID_PROJECT_' . $result . '_RESULT';
                    break;
                }
                if (Session::has('result')) {
                    DB::table($table_result)->where('PROJECT_CODE', $id)->where('YEAR', $data[$i]['YEAR'])->delete();
                    $result_indicator = DB::table($table_result)->where('PROJECT_CODE', $id)->first();
                    DB::table($table_indicator)->where('ID_PROJECT_' . $result . '_RESULT', $result_indicator->$result_indicator_id)->delete();
                }
                $indicator = DB::table($table_result)->where('PROJECT_CODE', $id)->where('QUARTER', $data[$i]['QUARTER'])->where('YEAR', $data[$i]['YEAR'])->get();
                if (count($indicator) <= 0) {
                    DB::table($table_result)->insert([
                        'PROJECT_CODE' => $id,
                        'QUARTER' => $data[$i]['QUARTER'],
                        'YEAR' => $data[$i]['YEAR'],
                        $result . '_RESULT' => $data[$i]['RESULT'],
                        'GOAL' => $data[$i]['GOAL'],
                        'ACTUAL' => $data[$i]['ACTUAL'],
                        'DT_CREATED' => date('Y-m-d'),
                        'USER_CREATED' => Session::get('username'),
                    ]);
                    $result_id = DB::getPdo()->lastInsertId();
                } else {
                    DB::table($table_result)->where('PROJECT_CODE', $id)->where('QUARTER', $data[$i]['QUARTER'])->where('YEAR', $data[$i]['YEAR'])->update([
                        'QUARTER' => $data[$i]['QUARTER'],
                        'YEAR' => $data[$i]['YEAR'],
                        $result . '_RESULT' => $data[$i]['RESULT'],
                        'GOAL' => $data[$i]['GOAL'],
                        'ACTUAL' => $data[$i]['ACTUAL'],
                    ]);

                    $result_table = DB::table($table_result)->where('PROJECT_CODE', $id)->where('QUARTER', $data[$i]['QUARTER'])->where('YEAR', $data[$i]['YEAR'])->first();
                    $result_txt = 'ID_PROJECT_' . $result . '_RESULT';
                    $result_id = $result_table->$result_txt;
                }


                foreach ($data[$i]['INDICATOR'] as $indicator_id) {
                    $result_indicator = DB::table($table_indicator)->where('ID_PROJECT_' . $result . '_RESULT', $result_id)->where('ID_MAST_INDICATOR', $indicator_id)->count();
                    if ($result_indicator <= 0) {
                        DB::table($table_indicator)->insert([
                            'ID_PROJECT_' . $result . '_RESULT' => $result_id,
                            'ID_MAST_INDICATOR' => $indicator_id,
                        ]);
                    }
                }
            }
        }

        return \redirect()->route('project.edit', ['id'=>$id]);
    }

    public function result_begin(Request $req) {
        $result = DB::table('project_begin_result')->where('PROJECT_CODE', $req->project_code)->where('QUARTER', $req->quarter)->where('YEAR', $req->year)->first();
        $checked = DB::table('view_result_begin')->where('PROJECT_CODE', $req->project_code)->where('QUARTER', $req->quarter)->where('YEAR', $req->year)->get();
        $indicator = DB::table('view_result')->where('PROJECT_CODE', $req->project_code)->get();
        $output = "";
        if (count($checked) > 0) {
            $a = 0;
            $checked = json_decode($checked);
            foreach ($indicator as $in) {
                $ck = '';
                foreach ($checked as $pi) {
                    if ($pi->ID_MAST_INDICATOR == $in->ID_MAST_INDICATOR) {
                        $ck = 'checked';
                        break;
                    }
                }
                $output .= "<div class='mt-checkbox-list'>";
                $output .= "<label class='mt-checkbox mt-checkbox-outline'>";
                $output .= "<input type='checkbox' name='INDICATOR[1][$a]' value='$in->ID_MAST_INDICATOR' $ck> $in->SUBJECT_NAME > $in->GOAL_NAME > $in->INDICATOR_NAME";
                $output .= "<span></span>";
                $output .= "</label>";
                $output .= "</div>";
                $a++;
            }
        } else {
            $a = 0;
            foreach ($indicator as $in) {
                $output .= "<div class='mt-checkbox-list'>";
                $output .= "<label class='mt-checkbox mt-checkbox-outline'>";
                $output .= "<input type='checkbox' name='INDICATOR[1][$a]' value='$in->ID_MAST_INDICATOR'> $in->SUBJECT_NAME > $in->GOAL_NAME > $in->INDICATOR_NAME";
                $output .= "<span></span>";
                $output .= "</label>";
                $output .= "</div>";
                $a++;
            }
        }
        $data['BEGIN_RESULT'] = isset($result->BEGIN_RESULT) ? $result->BEGIN_RESULT : '';
        $data['GOAL'] = isset($result->GOAL) ? $result->GOAL : '';
        $data['ACTUAL'] = isset($result->ACTUAL) ? $result->ACTUAL : '';
        $data['INDICATOR'] = $output;
        echo json_encode($data);
    }

    public function result_mid(Request $req) {
        $result = DB::table('project_mid_result')->where('PROJECT_CODE', $req->project_code)->where('QUARTER', $req->quarter)->where('YEAR', $req->year)->first();
        $checked = DB::table('view_result_mid')->where('PROJECT_CODE', $req->project_code)->where('QUARTER', $req->quarter)->where('YEAR', $req->year)->get();
        $indicator = DB::table('view_result')->where('PROJECT_CODE', $req->project_code)->get();
        $output = "";
        if (count($checked) > 0) {
            $a = 0;
            $checked = json_decode($checked);
            foreach ($indicator as $in) {
                $ck = '';
                foreach ($checked as $pi) {
                    if ($pi->ID_MAST_INDICATOR == $in->ID_MAST_INDICATOR) {
                        $ck = 'checked';
                        break;
                    }
                }
                $output .= "<div class='mt-checkbox-list'>";
                $output .= "<label class='mt-checkbox mt-checkbox-outline'>";
                $output .= "<input type='checkbox' name='INDICATOR[2][$a]' value='$in->ID_MAST_INDICATOR' $ck> $in->SUBJECT_NAME > $in->GOAL_NAME > $in->INDICATOR_NAME";
                $output .= "<span></span>";
                $output .= "</label>";
                $output .= "</div>";
                $a++;
            }
        } else {
            $a = 0;
            foreach ($indicator as $in) {
                $output .= "<div class='mt-checkbox-list'>";
                $output .= "<label class='mt-checkbox mt-checkbox-outline'>";
                $output .= "<input type='checkbox' name='INDICATOR[2][$a]' value='$in->ID_MAST_INDICATOR'> $in->SUBJECT_NAME > $in->GOAL_NAME > $in->INDICATOR_NAME";
                $output .= "<span></span>";
                $output .= "</label>";
                $output .= "</div>";
                $a++;
            }
        }
        $data['MID_RESULT'] = isset($result->MID_RESULT) ? $result->MID_RESULT : '';
        $data['GOAL'] = isset($result->GOAL) ? $result->GOAL : '';
        $data['ACTUAL'] = isset($result->ACTUAL) ? $result->ACTUAL : '';
        $data['INDICATOR'] = $output;
        echo json_encode($data);
    }

    public function result_end(Request $req) {
        $result = DB::table('project_end_result')->where('PROJECT_CODE', $req->project_code)->where('QUARTER', $req->quarter)->where('YEAR', $req->year)->first();
        $checked = DB::table('view_result_end')->where('PROJECT_CODE', $req->project_code)->where('QUARTER', $req->quarter)->where('YEAR', $req->year)->get();
        $indicator = DB::table('view_result')->where('PROJECT_CODE', $req->project_code)->get();
        $output = "";
        if (count($checked) > 0) {
            $a = 0;
            $checked = json_decode($checked);
            foreach ($indicator as $in) {
                $ck = '';
                foreach ($checked as $pi) {
                    if ($pi->ID_MAST_INDICATOR == $in->ID_MAST_INDICATOR) {
                        $ck = 'checked';
                        break;
                    }
                }
                $output .= "<div class='mt-checkbox-list'>";
                $output .= "<label class='mt-checkbox mt-checkbox-outline'>";
                $output .= "<input type='checkbox' name='INDICATOR[3][$a]' value='$in->ID_MAST_INDICATOR' $ck> $in->SUBJECT_NAME > $in->GOAL_NAME > $in->INDICATOR_NAME";
                $output .= "<span></span>";
                $output .= "</label>";
                $output .= "</div>";
                $a++;
            }
        } else {
            $a = 0;
            foreach ($indicator as $in) {
                $output .= "<div class='mt-checkbox-list'>";
                $output .= "<label class='mt-checkbox mt-checkbox-outline'>";
                $output .= "<input type='checkbox' name='INDICATOR[3][$a]' value='$in->ID_MAST_INDICATOR'> $in->SUBJECT_NAME > $in->GOAL_NAME > $in->INDICATOR_NAME";
                $output .= "<span></span>";
                $output .= "</label>";
                $output .= "</div>";
                $a++;
            }
        }
        $data['END_RESULT'] = isset($result->END_RESULT) ? $result->END_RESULT : '';
        $data['GOAL'] = isset($result->GOAL) ? $result->GOAL : '';
        $data['ACTUAL'] = isset($result->ACTUAL) ? $result->ACTUAL : '';
        $data['INDICATOR'] = $output;
        echo json_encode($data);
    }

    public function begin_destroy($id) {
        DB::table('project_begin_indicator')->where('ID_PROJECT_BEGIN_RESULT', $id)->delete();
        DB::table('project_begin_result')->where('ID_PROJECT_BEGIN_RESULT', $id)->delete();

        return back()->with('success', 'ลบผลลัพธ์สำเร็จ');
    }

    public function mid_destroy($id) {
        DB::table('project_mid_indicator')->where('ID_PROJECT_MID_RESULT', $id)->delete();
        DB::table('project_mid_result')->where('ID_PROJECT_MID_RESULT', $id)->delete();

        return back()->with('success', 'ลบผลลัพธ์สำเร็จ');
    }

    public function end_destroy($id) {
        DB::table('project_end_indicator')->where('ID_PROJECT_END_RESULT', $id)->delete();
        DB::table('project_end_result')->where('ID_PROJECT_END_RESULT', $id)->delete();

        return back()->with('success', 'ลบผลลัพธ์สำเร็จ');
    }
}
