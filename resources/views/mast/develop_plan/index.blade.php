@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
    <div class="page-content-wrapper">
        <div class="page-content">
          <ul class="page-breadcrumb breadcrumb">
            <li>
              <i class="icon-globe" style="font-size: 18px;"></i>
              <a href="{{ route('develop_plan.index') }}" class="breadcrumb-link">แผนยุทธศาสตร์</a>
            </li>
          </ul>
            @if (Session::has('success') || Session::has('error'))
            <div class="note note-{{ Session::has('success') ? 'success' : 'danger' }}">
                    <p> {{ Session::has('success') ? Session::get('success') : Session::get('error') }} </p>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div>
                            <a href="{{ route('develop_plan.create') }}" class="btn green"> เพิ่ม
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-checkable table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="80%" style="text-align: left;padding-left: 10px"> แผนยุทธศาสตร์ </th>
                                        <th width="5%"> แก้ไข </th>
                                        <th width="5%"> ลบ </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $row)
                                    <tr>
                                        <td style="text-align: left;padding-left: 10px"> {{ $row->PLAN_NAME }} </td>
                                        <td class="text-center"> <a href="{{ route('develop_plan.edit', ['id'=>$row->ID_MAST_DEV]) }}"><i class="icon-note"></i></a> </td>
                                        <td class="text-center"> <a href="{{ route('develop_plan.destroy', ['id'=>$row->ID_MAST_DEV]) }}" onclick="return confirm('ท่านต้องการลบ {{ $row->PLAN_NAME }} ใช่หรือไม่')"><i class="icon-trash"></i></a> </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
