<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;

class ProjectWeightController extends Controller
{
    public function index() {
      $planId = Session::get('plan_id');
      $subjectAll = DB::table('mast_subject')
      ->where('ID_MAST_DEV', $planId)
      ->where('STATUS', 'A')
      ->get();

      $i = 0;
      foreach ($subjectAll as $subject) {
        $subjects[$i]['data'] = $subject;
        $progress = DB::table('view_indicator_dashboard')->where('ID_MAST_SUBJECT', $subject->ID_MAST_SUBJECT)->first();
        $subjects[$i]['progress'] = $progress;

        $i++;
      }
      // echo "<pre>";
      // print_r($subjects[0]['progress']->TOTAL);
      // echo "</pre>";
      // exit();
      if (!count($subjectAll)) {
        return redirect()->route('subject.index');
      }

      return view('weight_data.dashboard', compact('subjects'));
    }

    public function goalTable($subjectId) {
      $subject = DB::table('mast_subject')->where('ID_MAST_SUBJECT', $subjectId)->first();
      $goals = DB::table('mast_goal')
      ->where('ID_MAST_SUBJECT', $subjectId)
      ->where('STATUS', 'A')
      ->get();
      $progress = DB::table('view_indicator_dashboard')->where('ID_MAST_SUBJECT', $subjectId)->first();
      $total = @$progress->TOTAL;

      $i = 0;
      foreach ($goals as $goal) {
        $data[$i]['goal_id'] = $goal->ID_MAST_GOAL;
        $data[$i]['goal_name'] = $goal->GOAL_NAME;

        $x = 0;
        // Select Indicator
        $indicator = DB::table('mast_indicator')
        ->where('ID_MAST_GOAL', $goal->ID_MAST_GOAL)
        ->where('STATUS', 'A')
        ->get();
        foreach ($indicator as $ind) {
          $data[$i]['indicator'][$x]['ind_id'] = $ind->ID_MAST_INDICATOR;
          $data[$i]['indicator'][$x]['ind_name'] = $ind->INDICATOR_NAME;

          $progress = DB::table('view_progress')->where('ID_MAST_INDICATOR', $ind->ID_MAST_INDICATOR)->first();
          $data[$i]['indicator'][$x]['progress'] = @$progress->PROGRESS;

          $x++;
        }

        $i++;
      }
      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";
      // exit();
      // echo $goalId;
      return view('weight_data.goal_table',
        compact(
          'subject',
          'data',
          'total'
        )
      );
    }

    public function indTable(Request $req, $indId) {
      $years = DB::table('mast_develop_year')->where('ID_MAST_DEV', Session::get('plan_id'))->orderBy('YEAR', 'desc')->get();
      $projectBegin = DB::table('view_project_begin')->where('ID_MAST_INDICATOR', $indId)->get();
      $projectMid = DB::table('view_project_mid')->where('ID_MAST_INDICATOR', $indId)->get();
      $projectEnd = DB::table('view_project_end')->where('ID_MAST_INDICATOR', $indId)->get();
      if (count($projectBegin)) {
        $resultProject = $projectBegin;
      }
      else if (count($projectMid)) {
        $resultProject = $projectMid;
      }
      else if (count($projectEnd)) {
        $resultProject = $projectEnd;
      }
      else {
        return redirect()->back()->with('error', 'ไม่มีโครงการไหนที่ตอบตัวชี้วัดนี้');
      }

      $i = 0;
      $y = isset($req->year) ? $req->year : date('Y')+543;
      $q = isset($req->quarter) ? $req->quarter : 4;

      foreach ($resultProject as $project) {
        $projData = DB::table('view_project_list')->where('PROJECT_CODE', $project->PROJECT_CODE)->first();
        $begin = DB::table('view_project_begin')->where('PROJECT_CODE', $project->PROJECT_CODE)->where('YEAR', $y)->where('QUARTER', $q)->first();
        $mid = DB::table('view_project_mid')->where('PROJECT_CODE', $project->PROJECT_CODE)->where('YEAR', $y)->where('QUARTER', $q)->first();
        $end = DB::table('view_project_end')->where('PROJECT_CODE', $project->PROJECT_CODE)->where('YEAR', $y)->where('QUARTER', $q)->first();
        $weight = DB::table('project_weight')->where('PROJECT_CODE', $project->PROJECT_CODE)->where('ID_MAST_INDICATOR', $indId)->first();

        $projects[$i]['PROJECT_CODE'] = $projData->PROJECT_CODE;
        $projects[$i]['PROJECT_NAME'] = $projData->PROJECT_NAME;
        $projects[$i]['DEPARTMENT_NAME'] = $projData->DEPARTMENT_NAME;
        $projects[$i]['BEGIN'] = $begin;
        $projects[$i]['MID'] = $mid;
        $projects[$i]['END'] = $end;
        $projects[$i]['DATA'] = $weight;

        $i++;
      }

      // echo "<pre>";
      // print_r($projects);
      // echo "</pre>";
      // exit();

      $subject = DB::table('view_national_assessment')->where('ID_MAST_INDICATOR', $indId)->first();

      return view('weight_data.ind_table', \compact(
        'projects',
        'indId',
        'subject',
        'years'
      ));
    }

    public function store(Request $req) {
      $countProject = count($req->PROJECT_CODE);
      for ($i = 0;$i < $countProject;$i++) {
        DB::table('project_weight')->where('PROJECT_CODE', $req->PROJECT_CODE[$i])->where('ID_MAST_INDICATOR', $req->ID_MAST_INDICATOR)->delete();

        DB::table('project_weight')->insert([
          'PROJECT_CODE' => $req->PROJECT_CODE[$i],
          'ID_MAST_INDICATOR' => $req->ID_MAST_INDICATOR,
          'WEIGHT' => $req->WEIGHT[$i],
          'SUCCESS' => $req->SUCCESS[$i],
          'STATUS' => @$req->STATUS[$i] ? 1 : 0
        ]);
      }
      // foreach ($req->PROJECT_CODE as $code) {
      //   echo $code . "<br />";
      // }

      return \redirect()->back();
    }
}
