@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
    <div class="page-content-wrapper">
        <div class="page-content">
          <ul class="page-breadcrumb breadcrumb">
            <li>
              <i class="icon-user" style="font-size: 18px;"></i>
              <a href="{{ route('user.index') }}" class="breadcrumb-link">ผู้ใช้งานระบบ</a>
              <span class="next"> > </span>
            </li>
            <li>
              <span class="breadcrumb-current">
                แก้ไขข้อมูลผู้ใช้งานระบบ
              </span>
            </li>
          </ul>
            @if ($msg = Session::get('data')['error'])
                <div class="note note-danger">
                    <p>{{ $msg }}</p>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            @php
                                $user = $data['user'];
                                $ex = explode(' ', $user->USER_NAME);
                                $fname = @$ex[0];
                                $lname = @$ex[1];
                            @endphp
                            <!-- BEGIN FORM-->
                            <form action="{{ route('user.update', ['id' => $user->USER_LOGIN]) }}" class="horizontal-form" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="portlet box blue">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-user"></i>ชื่อผู้ใช้
                                            </div>
                                            <div class="tools">
                                                <a href="javascript:;" class="collapse"> </a>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group {{ Session::has('data') ? 'has-error' : '' }}">
                                                        <label class="control-label">ชื่อผู้ใช้</label>
                                                        <input type="text" class="form-control" name="USER_LOGIN" maxlength="8" placeholder="ชื่อผู้ใช้" value="{{ $user->USER_LOGIN }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">รหัสผ่าน</label>
                                                        <input type="password" class="form-control" name="PASSWORD" placeholder="รหัสผ่าน">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">ยืนยันรหัสผ่าน</label>
                                                        <input type="password" class="form-control" placeholder="ยืนยันรหัสผ่าน">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="portlet box blue">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-user"></i>ข้อมูลผู้ใช้งาน
                                            </div>
                                            <div class="tools">
                                                <a href="javascript:;" class="collapse"> </a>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">ชื่อ</label>
                                                        <input type="text" name="USER_FNAME" class="form-control" placeholder="ชื่อ" value="{{ $fname }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">นามสกุล</label>
                                                        <input type="text" name="USER_LNAME" class="form-control" placeholder="นามสกุล" value="{{ $lname }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">สายงาน</label>
                                                        <select name="MINISTRY_CODE" class="bs-select form-control" id="ministry">
                                                            <option value="">-- เลือกสายงาน --</option>
                                                            @foreach ($data['ministry'] as $row)
                                                            <option value="{{ $row->MINISTRY_CODE }}" {{ isset($data['mint']->MINISTRY_CODE) && $data['mint']->MINISTRY_CODE == $row->MINISTRY_CODE ? 'selected' : '' }}> {{ $row->MINISTRY_NAME }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">สำนัก</label>
                                                        <select name="DEPARTMENT_CODE" class="bs-select form-control" id="department">
                                                            <option value="">-- เลือกสำนัก --</option>
                                                            @foreach ($data['department'] as $row)
                                                            <option value="{{ $row->DEPARTMENT_CODE }}" {{ $data['dept']->DEPARTMENT_CODE == $row->DEPARTMENT_CODE ? 'selected' : '' }}> {{ $row->DEPARTMENT_NAME }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">สิทธิ์ผู้ใช้งาน</label>
                                                        <select name="USER_TYPE" class="bs-select form-control">
                                                            <option value="U" {{ $user->USER_TYPE == 'U' ? 'selected' : '' }}> User </option>
                                                            <option value="L" {{ $user->USER_TYPE == 'L' ? 'selected' : '' }}> Leader </option>
                                                            <option value="S" {{ $user->USER_TYPE == 'S' ? 'selected' : '' }}> SysAdmin </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">สถานะ</label>
                                                        <select name="STATUS" class="bs-select form-control">
                                                            <option value="1" {{ $user->STATUS == '1' ? 'selected' : '' }}> ใช้งาน </option>
                                                            <option value="0" {{ $user->STATUS == '0' ? 'selected' : '' }}> ยกเลิก </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions left">
                                    <button type="submit" class="btn blue"><i class="fa fa-check"></i> บันทึก</button>
                                    <button type="reset" class="btn default">ยกเลิก</button>
                                </div>
                            </form>
                            <!-- END FORM-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#ministry').change(function() {
                var ministry_code = $(this).val();
                $.ajax({
                    url: "{{ route('user.select.ministry') }}",
                    method: 'GET',
                    data: {ministry_code:ministry_code},
                    dataType: 'JSON',
                    success: function(result) {
                        $('#department').html(result.output);
                    }
                })
            })
            $('#department').change(function() {
                var department_code = $(this).val();
                $.ajax({
                    url: "{{ route('user.select.department') }}",
                    method: 'GET',
                    data: {department_code:department_code},
                    dataType: 'JSON',
                    success: function(result) {
                        $('#username').val(result.output);
                    }
                })
            })
        })
    </script>
@endpush
