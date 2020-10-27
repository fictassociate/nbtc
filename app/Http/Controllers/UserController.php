<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;

class UserController extends Controller
{
    public function index() {
        $user = DB::table('view_user')->get();

        return view('mast.user.index')->with('data', $user);
    }

    public function create() {
        $department = DB::table('department')->where('STATUS', 'A')->get();
        $ministry = DB::table('ministry')->where('STATUS', 'A')->get();
        $data = [
            'department' => $department,
            'ministry' => $ministry,
        ];

        return view('mast.user.create')->with('data', $data);
    }

    public function store(Request $request) {

        $user_login = $request->get('USER_LOGIN');
        $password = Hash::make('12345678');
        $user_name = $request->get('USER_FNAME') . " " . $request->get('USER_LNAME');
        $user_type = $request->get('USER_TYPE');
        $department_code = $request->get('DEPARTMENT_CODE');

        $user = DB::table('user_pwd')->where('user_login', $user_login)->first();
        if (count($user) > 0) {
            $data = [
                'error' => 'ชื่อผู้ใช้นี้ถูกใช้งานแล้ว',
                'username' => $user_login,
                'fname' => $request->get('USER_FNAME'),
                'lname' => $request->get('USER_LNAME'),
                'user_type' => $user_type,
                'department_code' => $department_code,
            ];

            return back()->with('data', $data);
        }

        DB::table('user')->insert([
            'USER_LOGIN' => $user_login,
            'USER_NAME' => $user_name,
            'USER_TYPE' => $user_type,
            'STATUS' => 1,
            'DEPARTMENT_CODE' => $department_code,
        ]);
        DB::table('user_pwd')->insert([
            'USER_LOGIN' => $user_login,
            'PASSWORD' => $password,
        ]);

        return \redirect()->route('user.index')->with('success', 'เพิ่มผู้ใช้งาน ' . $user_name . ' สำเร็จ');
    }

    public function edit($id) {
        $user = DB::table('user')->where('user_login', $id)->first();
        $ministry = DB::table('ministry')->where('STATUS', 'A')->get();
        $department = DB::table('department')->where('STATUS', 'A')->get();
        $dept = DB::table('department')->where('DEPARTMENT_CODE', $user->DEPARTMENT_CODE)->where('STATUS', 'A')->first();
        $mint = DB::table('ministry')->where('MINISTRY_CODE', $dept->MINISTRY_CODE)->first();


        $data = [
            'department' => $department,
            'user' => $user,
            'ministry' => $ministry,
            'mint' => $mint,
            'dept' => $dept,
        ];

        return view('mast.user.edit')->with('data', $data);
    }

    public function update(Request $request, $id) {
        $user_name = $request->get('USER_FNAME') . " " . $request->get('USER_LNAME');
        $user_type = $request->get('USER_TYPE');
        $department_code = $request->get('DEPARTMENT_CODE');

        DB::table('user')->where('USER_LOGIN', $id)->update([
            'USER_NAME' => $user_name,
            'USER_TYPE' => $user_type,
            'STATUS' => $request->STATUS,
            'DEPARTMENT_CODE' => $department_code,
        ]);
        if ($request->get('PASSWORD') != "" && !empty($request->get('PASSWORD'))) {
            $password = Hash::make($request->get('PASSWORD'));
            DB::table('user_pwd')->where('USER_LOGIN', $id)->update([
                'PASSWORD' => $password,
            ]);
        }

        return \redirect()->route('user.index')->with('success', 'แก้ไขข้อมูลผู้ใช้งาน ' . $user_name . ' สำเร็จ');
    }

    public function select_ministry(Request $req) {
        $department = DB::table('department')->where('MINISTRY_CODE', $req->ministry_code)->get();
        $output = "";
        $output .= "<option value=''>-- เลือกสำนัก --</option>";
        foreach ($department as $dem) {
            $output .= "<option value='$dem->DEPARTMENT_CODE'> $dem->DEPARTMENT_NAME </option>";
        }

        $data = [
            'output' => $output,
        ];
        echo json_encode($data);
    }

    public function select_department(Request $req) {
        $department = DB::table('department')->where('DEPARTMENT_CODE', $req->department_code)->first();
        $user = Collect(DB::select("SELECT * FROM user_pwd WHERE USER_LOGIN LIKE '$req->department_code%' ORDER BY USER_LOGIN DESC"))->first();
        $number = substr($user->USER_LOGIN, 3) + 1;
        $number = $number < 10 ? 0 . $number:$number;

        $data = [
            'output' => $req->department_code . $number,
        ];
        echo json_encode($data);
    }
}
