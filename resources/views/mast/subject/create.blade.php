@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
    <div class="page-content-wrapper">
        <div class="page-content">
            {{-- <div class="note note-info">
                <p> A black page template with a minimal dependency assets to use as a base for any custom page you create </p>
            </div> --}}
            <ul class="page-breadcrumb breadcrumb">
              <li>
                <i class="icon-eyeglasses" style="font-size: 18px;"></i>
                <a href="{{ route('subject.index') }}" class="breadcrumb-link">ยุทธศาสตร์</a>
                <span class="next"> > </span>
              </li>
              <li>
                <span class="breadcrumb-current">
                  เพิ่มข้อมูลยุทธศาสตร์
                </span>
              </li>
            </ul>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="{{ route('subject.store') }}" class="horizontal-form" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">ยุทธศาสตร์</label>
                                                <input type="text" class="form-control" name="SUBJECT_NAME" placeholder="กรอกยุทธศาสตร์" maxlength="255">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">แผนยุทธศาสตร์</label>
                                                <select class="select2 form-control" name="ID_MAST_DEV">
                                                    @foreach ($data as $row)
                                                    <option value="{{ $row->ID_MAST_DEV }}">{{ $row->PLAN_NAME }}</option>
                                                    @endforeach
                                                </select>
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
@push('style')
    <style>
        .form .form-body {
            padding-top: 0px;
        }
    </style>
@endpush
