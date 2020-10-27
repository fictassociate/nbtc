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
                <i class="icon-drop" style="font-size: 18px;"></i>
                <a href="{{ route('goal.index') }}" class="breadcrumb-link">กลยุทธ์</a>
                <span class="next"> > </span>
              </li>
              <li>
                <span class="breadcrumb-current">
                  เพิ่มกลยุทธ์
                </span>
              </li>
            </ul>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="{{ route('goal.store') }}" class="horizontal-form" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="control-label">กลยุทธ์</label>
                                                <input type="text" class="form-control" name="GOAL_NAME" placeholder="กรอกกลยุทธ์" maxlength="255">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">แผนยุทธศาสตร์</label>
                                                <select class="select2 form-control" name="ID_MAST_DEV" id="plan">
                                                    @foreach ($data['plan'] as $row)
                                                    <option value="{{ $row->ID_MAST_DEV }}">{{ $row->PLAN_NAME }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">ยุทธศาสตร์</label>
                                                <select class="select2 form-control" name="ID_MAST_SUBJECT" id="subject">
                                                    @foreach ($data['subject'] as $row)
                                                    <option value="{{ $row->ID_MAST_SUBJECT }}">{{ $row->SUBJECT_NAME }}</option>
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
@push('script')
    <script>
        $(document).ready(function() {
            $('#plan').change(function() {
                var dev_id = $(this).val();
                $.ajax({
                    url: "{{ route('goal.select.subject') }}",
                    method: 'GET',
                    data: {dev_id:dev_id},
                    success: function(result) {
                        $('#subject').html(result);
                    }
                })
            })
        })
    </script>
@endpush
