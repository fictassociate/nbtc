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
                <i class="icon-social-dropbox" style="font-size: 18px;"></i>
                <a href="{{ route('project.index') }}" class="breadcrumb-link">โครงการทั้งหมด</a>
                <span class="next"> > </span>
              </li>
              <li>
                <span class="breadcrumb-current">
                  เพิ่มโครงการ
                </span>
              </li>
            </ul>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="{{ route('project.store') }}" id="project-form" class="horizontal-form" method="POST">
                                @csrf
                                <div class="form-body">
                                    <div class="portlet">
                                        <div class="portlet-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group {{ Session::has('data') ? 'has-error' : '' }}">
                                                        <h5>รหัสโครงการ</h5>
                                                        <input type="text" class="form-control" name="PROJECT_CODE" placeholder="กรอกรหัสโครงการ " required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <h5>ชื่อโครงการ</h5>
                                                        <input type="text" class="form-control" name="PROJECT_NAME" placeholder="" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <h5>หน่วยงานที่รับผิดชอบ</h5>
                                                        @if (Session::get('role') == "S")
                                                            <select name="DEPARTMENT" class="form-control select2">
                                                                @foreach ($data['department'] as $department)
                                                                    <option value="{{ $department->DEPARTMENT_CODE }}">{{ $department->DEPARTMENT_NAME }}</option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <input type="text" class="form-control" value="{{ Session::get('department') }}" readonly>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                  <div class="form-group">
                                                      <h5>ระยะเวลาดำเนินการ (เริ่มต้น / สิ้นสุด)</h5>
                                                      <div class="input-group input-large date-picker input-daterange" data-date="01/11/2563" data-date-format="dd/mm/yyyy">
                                                          <input type="text" class="form-control" name="DT_START" autocomplete="off">
                                                          <span class="input-group-addon"> ถึง </span>
                                                          <input type="text" class="form-control" name="DT_END" autocomplete="off">
                                                      </div>
                                                  </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="portlet box blue">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-user"></i>{{ $data['MastPlan'][0]->PLAN_NAME }}
                                            </div>
                                            <div class="tools">
                                                <a href="javascript:;" class="collapse"> </a>
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    @php
                                                        $i = 1;
                                                        $a = 1;
                                                        $b = 1;
                                                    @endphp
                                                    <ul>
                                                        <h5>ประเด็น</h5>
                                                        @foreach ($data['MastSubject'] as $subject)
                                                            @if ($data['MastPlan'][0]->ID_MAST_DEV == $subject->ID_MAST_DEV)
                                                                <li>
                                                                    <div class="mt-checkbox-list">
                                                                        <label class="mt-checkbox mt-checkbox-outline">
                                                                            <input type="checkbox" rel-section="{{ $i }}" class="subject subject-section-{{ $i }}" name="ID_MAST_SUBJECT[]" value="{{ $subject->ID_MAST_SUBJECT }}"> {{ $subject->SUBJECT_NAME }}
                                                                            <span></span>
                                                                        </label>
                                                                    </div>
                                                                    <ul class="goal-area-{{ $i }}">
                                                                        <h5>กลยุทธ์</h5>
                                                                        @foreach ($data['MastGoal'] as $goal)
                                                                            @if ($goal->ID_MAST_SUBJECT == $subject->ID_MAST_SUBJECT)
                                                                                <li>
                                                                                    <div class="mt-checkbox-list">
                                                                                        <label class="mt-checkbox mt-checkbox-outline">
                                                                                            <input type="checkbox" rel-section="{{ $a }}" class="goal goal-section-{{ $a }}" name="ID_MAST_GOAL[]" value="{{ $goal->ID_MAST_GOAL }}"> {{ $goal->GOAL_NAME }}
                                                                                            <span></span>
                                                                                        </label>
                                                                                    </div>
                                                                                    <ul class="indicator-area-{{ $a }}">
                                                                                        <h5>ตัวชี้วัด</h5>
                                                                                        @foreach ($data['MastIndicator'] as $indicator)
                                                                                            @if ($indicator->ID_MAST_GOAL == $goal->ID_MAST_GOAL)
                                                                                                <li>
                                                                                                    <div class="mt-checkbox-list">
                                                                                                        <label class="mt-checkbox mt-checkbox-outline">
                                                                                                            <input type="checkbox" name="ID_MAST_INDICATOR[]" value="{{ $indicator->ID_MAST_INDICATOR }}"> {{ $indicator->INDICATOR_NAME }}
                                                                                                            <span></span>
                                                                                                        </label>
                                                                                                    </div>
                                                                                                </li>
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </ul>
                                                                                </li>
                                                                            @endif
                                                                            @php
                                                                                $a++;
                                                                            @endphp
                                                                        @endforeach
                                                                    </ul>
                                                                </li>
                                                            @endif
                                                            @php
                                                                $i++;
                                                            @endphp
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h5>กิจกรรมหลักภายใต้โครงการ</h5>
                                                <textarea cols="30" rows="5" class="form-control" name="SUMMARY_PROCESS"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <h5>งบประมาณ</h5>
                                                <button type="button" id="add_year" class="btn btn-info btn-xs">+</button>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" width="20%">ปี พ.ศ.</th>
                                                            <th class="text-center" width="40%">งบประมาณที่เสนอขอ (บาท)</th>
                                                            <th class="text-center" width="40%">งบประมาณที่ได้รับอนุมัติจริง (บาท)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="show_year">
                                                        <tr>
                                                            <td class="text-center"><input type="text" class="form-control check-number" name="YEAR[]" maxlength="4"></td>
                                                            <td><input type="text" class="form-control check-number" name="BUDGET2[]"></td>
                                                            <td><input type="text" class="form-control check-number" name="BUDGET[]"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions text-center">
                                    <button type="submit" class="btn blue"><i class="fa fa-check"></i> บันทึก</button>
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
        ul {list-style-type: none;}
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700
        }
    </style>
@endpush
@push('script')
    <script>
        $(document).ready(function() {
            $('#add_year').click(() => {
              $('#show_year')
              .append("<tr><td><input type='text' name='YEAR[]' \
              class='form-control'></td><td><input type='text' name='BUDGET2[]' \
              class='form-control'></td><td><input type='text' name='BUDGET[]' \
              class='form-control'></td></tr>");
            })

            $("ul[class^='goal-area-']").hide();
            $("ul[class^='indicator-area-']").hide();
            $('.subject').click(function() {
                var section = $(this).attr('rel-section');
                if($('.subject-section-' + section).is(":checked")){
                    $('.goal-area-' + section).show();
                } else {
                    $('.goal-area-' + section).hide();
                }
            })

            $('.goal').click(function() {
                var section = $(this).attr('rel-section');
                if($('.goal-section-' + section).is(":checked")){
                    $('.indicator-area-' + section).show();
                } else {
                    $('.indicator-area-' + section).hide();
                }
            })

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
