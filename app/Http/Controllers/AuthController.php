<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use DB;

class AuthController extends Controller
{
    public function index() {
        // echo Hash::make('1q2w3e4r');exit();

        return view('login.index');
    }

    public function login(Request $req) {
        $this->validate($req, [
            'USER_LOGIN' => 'required',
            'PASSWORD' => 'required',
        ]);
        $user_data = [
            'USER_LOGIN' => $req->get('USER_LOGIN'),
            'PASSWORD' => $req->get('PASSWORD'),
        ];

        $user = DB::table('user as u')
        ->join('user_pwd as up', 'up.USER_LOGIN', 'u.USER_LOGIN')
        ->where('u.USER_LOGIN', $user_data['USER_LOGIN'])
        ->where('u.STATUS', 1)
        ->first();
        $MastPlan = DB::table('mast_develop_plan')->where('STATUS', 'A')->orderBy('ID_MAST_DEV', 'desc')->get();
        if ($user && \password_verify($user_data['PASSWORD'], $user->PASSWORD)) {
            $data = DB::table('view_user')->where('USER_LOGIN', $user->USER_LOGIN)->first();
            Session::put('username', $data->USER_LOGIN);
            Session::put('name', $data->USER_NAME);
            Session::put('role', $user->USER_TYPE);
            Session::put('department', $data->DEPARTMENT_NAME);
            Session::put('plan', $MastPlan);
            Session::put('plan_name', $MastPlan[0]->PLAN_NAME);
            Session::put('plan_id', $MastPlan[0]->ID_MAST_DEV);

            return \redirect()->route('index');
        } else {
            return \back()->with('error', 'ชื่อผู้ใช้และรหัสผ่านไม่ถูกต้อง กรุณาลองใหม่');
        }
    }

    public function logout() {
        \Session::flush();

        return redirect()->route('login.index');
    }
}
