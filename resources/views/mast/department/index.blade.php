@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-head">
                <div class="page-title">
                    <h1>หน่วยงาน
                        {{-- <small>blank page layout</small> --}}
                    </h1>
                </div>
            </div>
            @if ($msg = Session::get('success'))
                <div class="note note-info">
                    <p>{{ $msg }}</p>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div>
                            <a href="{{ route('department.create') }}" class="btn green"> เพิ่ม
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-checkable table-bordered table-hover" id="sample_1">
                                <thead>
                                    <tr>
                                        <th width="10%"> ลำดับ. </th>
                                        <th class="text-center" width="10%"> รหัส </th>
                                        <th width="60%"> หน่วยงาน </th>
                                        <th class="text-center" width="15%"> สถานะ </th>
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
                                        <td class="text-center"> {{ $row->DEPARTMENT_CODE }} </td>
                                        <td> {{ $row->DEPARTMENT_NAME }} </td>
                                        <td class="text-center"> <button type="button" class="btn btn-{{ $row->STATUS == 'A' ? 'info' : 'danger' }}">{{ $row->DEPARTMENT_STATUS }}</button> </td>
                                        <td class="text-center"> <a href="{{ route('department.edit', ['department_code'=>$row->DEPARTMENT_CODE]) }}" class="btn yellow"><i class="icon-note"></i></a> </td>
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
