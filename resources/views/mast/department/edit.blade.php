@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
    <div class="page-content-wrapper">
        <div class="page-content">
            {{-- <div class="note note-info">
                <p> A black page template with a minimal dependency assets to use as a base for any custom page you create </p>
            </div> --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon-note"></i>
                                <span class="caption-subject bold uppercase">หน่วยงาน</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="{{ route('department.update', ['department_code'=>$data->DEPARTMENT_CODE]) }}" method="POST" class="horizontal-form">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group {{ Session::has('data') ? 'has-error' : '' }}">
                                                <label class="control-label">รหัสหน่วยงาน</label>
                                                <input type="text" class="form-control" name="DEPARTMENT_CODE" placeholder="รหัสหน่วยงาน" maxlength="20" value="{{ $data->DEPARTMENT_CODE }}" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="control-label">ชื่อหน่วยงาน</label>
                                                <input type="text" class="form-control" name="DEPARTMENT_NAME" placeholder="ชื่อหน่วยงาน" maxlength="150" value="{{ $data->DEPARTMENT_NAME }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label class="control-label">สถานะ</label>
                                                <select name="STATUS" class="form-control">
                                                    <option value="A" {{ $data->STATUS == 'A' ? 'selected' : '' }}>ใช้งาน</option>
                                                    <option value="I" {{ $data->STATUS == 'I' ? 'selected' : '' }}>ไม่ใช้งาน</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn green"><i class="fa fa-check"></i> บันทึก</button>
                                        </div>
                                    </div>
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
        });
    </script>
@endpush
