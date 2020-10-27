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
                เพิ่มผู้ใช้งานระบบ
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
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-plus"></i>
                                <span class="caption-subject bold uppercase">เพิ่มผู้ใช้งานระบบ</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="{{ route('user.store') }}" class="horizontal-form" method="POST">
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
                                                <div class="col-md-12">
                                                    <div class="form-group {{ Session::has('data') ? 'has-error' : '' }}">
                                                        <label class="control-label">ชื่อผู้ใช้</label>
                                                        <input type="text" class="form-control" name="USER_LOGIN" id="username" maxlength="8" placeholder="ชื่อผู้ใช้" value="{{ Session::has('data') ? Session::get('data')['username'] : '' }}" readonly>
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
                                                        <input type="text" name="USER_FNAME" class="form-control" placeholder="ชื่อ" value="{{ Session::has('data') ? Session::get('data')['fname'] : '' }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">นามสกุล</label>
                                                        <input type="text" name="USER_LNAME" class="form-control" placeholder="นามสกุล" value="{{ Session::has('data') ? Session::get('data')['lname'] : '' }}" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">สายงาน</label>
                                                        <select name="MINISTRY_CODE" class="bs-select form-control" id="ministry">
                                                            <option value="">-- เลือกสายงาน --</option>
                                                            @foreach ($data['ministry'] as $row)
                                                            <option value="{{ $row->MINISTRY_CODE }}" {{ Session::has('data') && Session::get('data')['ministry_code'] == $row->MINISTRY_CODE ? 'selected' : '' }}> {{ $row->MINISTRY_NAME }} </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">สำนัก</label>
                                                        <select name="DEPARTMENT_CODE" class="bs-select form-control" id="department">
                                                            <option value="">-- เลือกสำนัก --</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="control-label">สิทธิ์ผู้ใช้งาน</label>
                                                        <select name="USER_TYPE" class="bs-select form-control">
                                                            <option value="U" {{ Session::has('data') && Session::get('data')['user_type'] == 'U' ? 'selected' : '' }}> User </option>
                                                            <option value="L" {{ Session::has('data') && Session::get('data')['user_type'] == 'L' ? 'selected' : '' }}> Leader </option>
                                                            <option value="S" {{ Session::has('data') && Session::get('data')['user_type'] == 'S' ? 'selected' : '' }}> SysAdmin </option>
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
