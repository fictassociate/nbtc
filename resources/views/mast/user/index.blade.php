@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
    <div class="page-content-wrapper">
        <div class="page-content">
          <ul class="page-breadcrumb breadcrumb">
            <li>
              <i class="icon-user" style="font-size: 18px;"></i>
              <a href="{{ route('user.index') }}" class="breadcrumb-link">ผู้ใช้งานระบบ</a>
            </li>
          </ul>
            @if ($msg = Session::get('success'))
                <div class="note note-info">
                    <p>{{ $msg }}</p>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div>
                            <a href="{{ route('user.create') }}" class="btn green"> เพิ่ม
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-checkable table-bordered table-hover" id="sample_1">
                                <thead>
                                    <tr>
                                        <th width="10%"> ลำดับ. </th>
                                        <th width="25%"> ชื่อผู้ใช้ </th>
                                        <th width="40%"> หน่วยงาน </th>
                                        <th width="10%"> สิทธิ์ </th>
                                        <th width="10%"> สถานะ </th>
                                        <th width="5%"> แก้ไข </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($data as $row)
                                    <tr>
                                        <td> {{ $i++ }} </td>
                                        <td> {{ $row->USER_NAME }} </td>
                                        <td> {{ $row->DEPARTMENT_NAME }} </td>
                                        <td> {{ $row->USER_TYPE }} </td>
                                        <td class="text-center"> <button type="button" class="btn btn-{{ $row->STATUS_ID == 1 ? 'info' : 'danger' }}">{{ $row->STATUS_NAME }}</button> </td>
                                        <td><a href="{{ route('user.edit', ['id'=>$row->USER_LOGIN]) }}" class="btn yellow"><i class="icon-note"></i></a></td>
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
