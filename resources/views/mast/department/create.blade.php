@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
    <div class="page-content-wrapper">
        <div class="page-content">
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
                                <span class="caption-subject bold uppercase">หน่วยงาน</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="{{ route('department.store') }}" method="POST" class="horizontal-form">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group {{ Session::has('data') ? 'has-error' : '' }}">
                                                <label class="control-label">รหัสหน่วยงาน</label>
                                                <input type="text" class="form-control" name="DEPARTMENT_CODE" placeholder="รหัสหน่วยงาน" maxlength="20" value="{{ Session::has('data') ? Session::get('data')['department_code'] : '' }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="control-label">ชื่อหน่วยงาน</label>
                                                <input type="text" class="form-control" name="DEPARTMENT_NAME" placeholder="ชื่อหน่วยงาน" maxlength="150" value="{{ Session::has('data') ? Session::get('data')['department_name'] : '' }}" required>
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
