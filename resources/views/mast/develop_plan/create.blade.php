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
                <i class="icon-globe" style="font-size: 18px;"></i>
                <a href="{{ route('develop_plan.index') }}" class="breadcrumb-link">แผนยุทธศาสตร์</a>
                <span class="next"> > </span>
              </li>
              <li>
                <span class="breadcrumb-current">
                  สร้างแผนยุทธศาสตร์
                </span>
              </li>
            </ul>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="{{ route('develop_plan.store') }}" id="plan_form" method="POST" class="horizontal-form">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">ชื่อแผน</label>
                                                <input type="text" class="form-control" name="PLAN_NAME" placeholder="กรอกชื่อแผนยุทธศาสตร์" maxlength="255" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">ปี (เริ่ม)</label>
                                                <input type="number" class="form-control" id="start_year" name="start_year" min="2550" max="2600" required>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">ปี (สิ้นสุด)</label>
                                                <input type="number" class="form-control" id="end_year" name="end_year" min="2550" max="2600" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="mt-radio">
                                                    <input type="radio" name="import" class="hide_import" value="1" checked> สร้างแผนใหม่
                                                    <span></span>
                                                </label>
                                                <label class="mt-radio">
                                                    <input type="radio" name="import" id="import" value="2"> นำเข้าข้อมูล
                                                    <span></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group" id="import_data">
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
@push('script')
    <script>
        $(document).ready(function() {
            var imp = $('#import_data');

            imp.hide();
            $('.hide_import').click(function() {
                imp.hide();
            });
            $('#import').click(function() {
                imp.show();
            });


            $('#plan_form').submit(function(event) {
                if ($('#start_year').val() > $('#end_year').val()) {
                    event.preventDefault();
                    alert('ปีที่เริ่มต้นต้องน้อยกว่าหรือเท่ากับปีที่สิ้นสุด');
                }
            })
        });
    </script>
@endpush
