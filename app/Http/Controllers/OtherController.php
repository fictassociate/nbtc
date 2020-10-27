<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;

class OtherController extends Controller
{
    public function dropdown_plan(Request $req) {
        $MastPlan = DB::table('mast_develop_plan')->where('ID_MAST_DEV', $req->get('plan_id'))->first();
        Session::put('plan_name', $MastPlan->PLAN_NAME);
        Session::put('plan_id', $MastPlan->ID_MAST_DEV);

        return back();
    }
}
