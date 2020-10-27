<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class DepartmentController extends Controller
{
    public function index() {
        $department = DB::table('view_department')->get();

        return view('mast.department.index')->with('data', $department);
    }

    public function create() {
        return view('mast.department.create');
    }

    public function store(Request $request) {
        $department_code = $request->get('DEPARTMENT_CODE');
        $department_name = $request->get('DEPARTMENT_NAME');

        $department = DB::table('department')->where('DEPARTMENT_CODE', $department_code)->first();
        if (count($department) > 0) {
            $data = [
                'error' => 'รหัสหน่วยงานนี้ถูกใช้งานแล้วโดย ' . $department->DEPARTMENT_NAME,
                'department_code' => $department_code,
                'department_name' => $department_name,
            ];
            return back()->with('data', $data);
        }

        DB::table('department')->insert([
            'DEPARTMENT_CODE' => $department_code,
            'DEPARTMENT_NAME' => $department_name,
            'STATUS' => 'A',
        ]);

        return \redirect()->route('department.index')->with('success', 'เพิ่มหน่วยงาน ' . $department_name . ' สำเร็จ');
    }

    public function edit($department_code) {
        $department = DB::table('department')->where('DEPARTMENT_CODE', $department_code)->first();

        return view('mast.department.edit')->with('data', $department);
    }

    public function update(Request $request, $department_code) {
        $department = DB::table('department')->where('DEPARTMENT_CODE', $department_code)->first();
        $department_name = $request->get('DEPARTMENT_NAME');
        DB::table('department')->where('DEPARTMENT_CODE', $department_code)->update([
            'DEPARTMENT_NAME' => $department_name,
            'STATUS' => $request->STATUS,
        ]);

        return \redirect()->route('department.index')->with('success', 'แก้ไขข้อมูลสำเร็จ');
    }

    public function destroy($department_code) {
        $department = DB::table('department')->where('DEPARTMENT_CODE', $department_code)->delete();

        return \redirect()->route('department.index');
    }
}
