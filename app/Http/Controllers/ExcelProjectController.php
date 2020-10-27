<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;

class ExcelProjectController extends Controller
{
    public function index() {
        return view('excel.project');
    }

    public function store(Request $request) {
        $file = $request->file('upload');

        //Move Uploaded File
        $filename = strtotime('now') . ".csv";
        $destinationPath = 'upload';
        $file->move($destinationPath,$filename);
        $file = fopen("upload/" . $filename, "r");
        while (($line = fgetcsv($file)) !== FALSE) {
            //$line[0] = '1004000018' in first iteration

            $Project = DB::table('project')->insert([
                'PROJECT_CODE' => $line[0],
                'PROJECT_NAME' => $line[1],
                'OBJECTIVE' => $line[2],
                'PROVINCE_CODE' => 10,
                'SUMMARY_PROCESS' => $line[5],
                'DT_START' => $line[3],
                'DT_END' => $line[4],
                'USER_LOGIN' => Session::get('username'),
            ]);

            $MastYear = DB::table('mast_develop_year')->where('ID_MAST_DEV', Session::get('plan_id'))->get();
            foreach ($MastYear as $year) {
                DB::table('project_budget')->insert([
                    'PROJECT_CODE' => $line[0],
                    'BUDGET_YEAR' => $year->YEAR,
                    'BUDGET' => 0,
                    'DT_CREATED' => date('Y-m-d'),
                    'USER_LOGIN' => Session::get('username'),
                ]);
            }

            DB::table('project_indicator')->insert([
                'PROJECT_CODE' => $line[0],
                'ID_MAST_INDICATOR' => $line[6],
                'DT_CREATED' => date('Y-m-d'),
                'USER_LOGIN' => Session::get('username'),
            ]);
        }
        fclose($file);

        return \redirect()->route('project.index');
    }
}
