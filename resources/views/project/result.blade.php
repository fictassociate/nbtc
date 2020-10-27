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
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="{{ route('project.result.store', ['id'=>$data['Result'][0]->PROJECT_CODE]) }}" class="horizontal-form" method="POST">
                                @csrf
                                <input type="hidden" id="project_code" value="{{ $data['Result'][0]->PROJECT_CODE }}">
                                <div class="form-body">
                                    <div class="portlet">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-user"></i>โครงการ – เพิ่ม / แก้ไข ผลผลิต/ผลลัพธ์
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="control-label">ผลผลิต</label>
                                                        <textarea cols="30" rows="5" class="form-control" name="OUTCOME">{{ Session::has('outcome') ? Session::get('outcome') : $data['project']->OUTCOME }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <img src="{{ asset('images/result/example_result.png') }}" style="width: 100%">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    @for ($i = 1; $i < 4; $i++)
                                                        <div class="form-group">
                                                            <div class="col-md-12">
                                                                @php
                                                                    if ($i == 1) {
                                                                        $color = "green";
                                                                        $result = "ผลลัพธ์ขั้นต้น";
                                                                        $project_result = $data['begin_result'];
                                                                        $project_indicator = json_decode($data['begin_indicator']);
                                                                        $var = "BEGIN_RESULT";
                                                                    } else if ($i == 2) {
                                                                        $color = "yellow";
                                                                        $result = "ผลลัพธ์ขั้นกลาง";
                                                                        $project_result = $data['mid_result'];
                                                                        $project_indicator = json_decode($data['mid_indicator']);
                                                                        $var = "MID_RESULT";
                                                                    } else if ($i == 3) {
                                                                        $color = "grey";
                                                                        $result = "ผลลัพธ์ขั้นปลาย";
                                                                        $project_result = $data['end_result'];
                                                                        $project_indicator = json_decode($data['end_indicator']);
                                                                        $var = "END_RESULT";
                                                                    }

                                                                    // echo "<pre>";
                                                                    // print_r($sess);
                                                                    // echo "</pre>";
                                                                    // exit();
                                                                @endphp
                                                                <div class="portlet {{ $color }} box">
                                                                    <div class="portlet-title">
                                                                        <div class="caption">
                                                                            <i class="fa fa-cogs"></i>
                                                                            {{ $result }}
                                                                        </div>
                                                                        <div class="tools">
                                                                            <a href="javascript:;" class="expand"> </a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="portlet-body" style="display: none;">
                                                                        <div class="row">
                                                                            <label class="control-label col-md-2">ไตรมาส</label>
                                                                            <div class="col-md-2">
                                                                                <select class="form-control" id="quarter-{{ $i }}" name="QUARTER[{{ $i }}]">
                                                                                    @for ($y = 1; $y < 5; $y++)
                                                                                        {{-- <option value="{{ $y }}" {{ @$project_result->QUARTER == $y ? 'selected' : '' }}>{{ $y }}</option> --}}
                                                                                        <option value="{{ $y }}" {{ $y == 4 ? 'selected' : '' }}>{{ $y }}</option>
                                                                                    @endfor
                                                                                </select>
                                                                            </div>
                                                                            <label class="col-md-2"></label>
                                                                            <label class="control-label col-md-2">ปี พ.ศ.</label>
                                                                            <div class="col-md-2">
                                                                                <select class="form-control" id="year-{{ $i }}" name="YEAR[{{ $i }}]">
                                                                                    @foreach ($data['MastYear'] as $year)
                                                                                        {{-- <option value="{{ $year->YEAR }}" {{ @$project_result->YEAR == $year->YEAR ? 'selected' : '' }}>{{ $year->YEAR }}</option> --}}
                                                                                        <option value="{{ $year->YEAR }}" {{ $year->YEAR == 2563 ? 'selected' : '' }}>{{ $year->YEAR }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <div class="row">
                                                                            <label class="col-md-2 control-label">{{ $result }}</label>
                                                                            <div class="col-md-10">
                                                                                <div class="form-group">
                                                                                    <input type="text" class="form-control" id="result-{{ $i }}" name="RESULT[{{ $i }}]" value="{{ @$project_result->$var }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <img src="{{ asset('images/result/example_result' . $i . '.png') }}" style="width: 100%">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <label class="col-md-2 control-label">ตัวชี้วัด</label>
                                                                            <div class="col-md-10">
                                                                                <div class="form-group" id="indicator-{{$i}}">
                                                                                    @php
                                                                                        $a = 0;
                                                                                    @endphp
                                                                                    @foreach ($data['Result'] as $result)
                                                                                        <div class="mt-checkbox-list">
                                                                                            <label class="mt-checkbox mt-checkbox-outline">
                                                                                                @php
                                                                                                    $ck = '';
                                                                                                    foreach ($project_indicator as $pi) {
                                                                                                        if ($pi->ID_MAST_INDICATOR == $result->ID_MAST_INDICATOR) {
                                                                                                            $ck = 'checked';
                                                                                                            break;
                                                                                                        }
                                                                                                    }
                                                                                                @endphp
                                                                                                <input type="checkbox" name="INDICATOR[{{ $i }}][{{ $a }}]" value="{{ $result->ID_MAST_INDICATOR }}" {{ $ck }}> {{ $result->SUBJECT_NAME }} > {{ $result->GOAL_NAME }} > {{ $result->INDICATOR_NAME }}
                                                                                                <span></span>
                                                                                            </label>
                                                                                        </div>
                                                                                        @php
                                                                                            $a++;
                                                                                        @endphp
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <hr>
                                                                        <div class="row">
                                                                            <label class="col-md-2 control-label">ค่ากลยุทธ์ของโครงการ</label>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <input type="number" name="GOAL[{{ $i }}]" id="goal-{{$i}}" class="form-control check-number" value="{{ @$project_result->GOAL }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row">
                                                                            <label class="col-md-2 control-label">ผลการดำเนินการจริง</label>
                                                                            <div class="col-md-3">
                                                                                <div class="form-group">
                                                                                    <input type="number" name="ACTUAL[{{ $i }}]" id="actual-{{$i}}" class="form-control check-number" value="{{ @$project_result->ACTUAL }}">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions text-center">
                                    <button type="submit" class="btn blue"> เพิ่ม / แก้ไข ผลผลิต/ผลลัพธ์ </button>
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
            var project_code = $('#project_code').val();
            $('#quarter-1').change(function() {
                var quarter = $(this).val();
                var year = $('#year-1').val();
                $.ajax({
                    url: "{{ route('project.result.begin') }}",
                    method: 'GET',
                    data: {quarter:quarter,year:year,project_code:project_code},
                    dataType: 'JSON',
                    success: function(result) {
                        $('#result-1').val(result.BEGIN_RESULT)
                        $('#goal-1').val(result.GOAL)
                        $('#actual-1').val(result.ACTUAL)
                        $('#indicator-1').html(result.INDICATOR)
                    }
                })
            });
            $('#year-1').change(function() {
                var year = $(this).val();
                var quarter = $('#quarter-1').val();
                $.ajax({
                    url: "{{ route('project.result.begin') }}",
                    method: 'GET',
                    data: {quarter:quarter,year:year,project_code:project_code},
                    dataType: 'JSON',
                    success: function(result) {
                        $('#result-1').val(result.BEGIN_RESULT)
                        $('#goal-1').val(result.GOAL)
                        $('#actual-1').val(result.ACTUAL)
                        $('#indicator-1').html(result.INDICATOR)
                    }
                })
            });

            $('#quarter-2').change(function() {
                var quarter = $(this).val();
                var year = $('#year-2').val();
                $.ajax({
                    url: "{{ route('project.result.mid') }}",
                    method: 'GET',
                    data: {quarter:quarter,year:year,project_code:project_code},
                    dataType: 'JSON',
                    success: function(result) {
                        $('#result-2').val(result.MID_RESULT)
                        $('#goal-2').val(result.GOAL)
                        $('#actual-2').val(result.ACTUAL)
                        $('#indicator-2').html(result.INDICATOR)
                    }
                })
            });
            $('#year-2').change(function() {
                var year = $(this).val();
                var quarter = $('#quarter-2').val();
                $.ajax({
                    url: "{{ route('project.result.mid') }}",
                    method: 'GET',
                    data: {quarter:quarter,year:year,project_code:project_code},
                    dataType: 'JSON',
                    success: function(result) {
                        $('#result-2').val(result.MID_RESULT)
                        $('#goal-2').val(result.GOAL)
                        $('#actual-2').val(result.ACTUAL)
                        $('#indicator-2').html(result.INDICATOR)
                    }
                })
            });

            $('#quarter-3').change(function() {
                var quarter = $(this).val();
                var year = $('#year-3').val();
                $.ajax({
                    url: "{{ route('project.result.end') }}",
                    method: 'GET',
                    data: {quarter:quarter,year:year,project_code:project_code},
                    dataType: 'JSON',
                    success: function(result) {
                        $('#result-3').val(result.END_RESULT)
                        $('#goal-3').val(result.GOAL)
                        $('#actual-3').val(result.ACTUAL)
                        $('#indicator-3').html(result.INDICATOR)
                    }
                })
            });
            $('#year-3').change(function() {
                var year = $(this).val();
                var quarter = $('#quarter-3').val();
                $.ajax({
                    url: "{{ route('project.result.end') }}",
                    method: 'GET',
                    data: {quarter:quarter,year:year,project_code:project_code},
                    dataType: 'JSON',
                    success: function(result) {
                        $('#result-3').val(result.END_RESULT)
                        $('#goal-3').val(result.GOAL)
                        $('#actual-3').val(result.ACTUAL)
                        $('#indicator-3').html(result.INDICATOR)
                    }
                })
            });

            $(".check-number").keydown(function(event) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ( $.inArray(event.keyCode,[46,8,9,27,13,190]) !== -1 ||
                    // Allow: Ctrl+A
                    (event.keyCode == 65 && event.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (event.keyCode >= 35 && event.keyCode <= 39)) {
                        // let it happen, don't do anything
                        return;
                }
                else {
                    // Ensure that it is a number and stop the keypress
                    if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                        event.preventDefault();
                    }
                }
            });
        })
    </script>
@endpush
