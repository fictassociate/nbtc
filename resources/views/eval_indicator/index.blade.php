@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="page-head">
                <div class="page-title">
                    <h1>ตัวชี้วัด - การประเมิน
                        {{-- <small>blank page layout</small> --}}
                    </h1>
                </div>
            </div>
            {{-- <div class="note note-info">
                <p> A black page template with a minimal dependency assets to use as a base for any custom page you create </p>
            </div> --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <div class="row">
                                <div class="col-md-3">
                                    <h4>ปี</h4>
                                    <div class="input-group">
                                        <select class="form-control select2" id="dev_year">
                                            @foreach ($data['year'] as $year)
                                                <option value="{{ $year->YEAR }}">{{ $year->YEAR }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <h4>แผนยุทธศาสตร์</h4>
                                    <div class="input-group">
                                        <select class="select2 form-control" id="subject">
                                            @foreach ($data['subject'] as $row)
                                            <option value="{{ $row->ID_MAST_SUBJECT }}">{{ $row->SUBJECT_NAME }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('eval.indicator.update', ['id'=>Session::get('plan_id')]) }}" method="POST" id="form-eval">
                        @csrf
                        <div class="portlet light bordered">
                            <input type="hidden" name="YEAR" id="data_year">
                            <div class="portlet-body">
                                <div class="table-scrollable">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="vertical-align: middle;font-size: 17px" width="50%"> รายการ </th>
                                                <th style="vertical-align: top;font-size: 17px"> น้ำหนัก <br> (%)</th>
                                                <th style="vertical-align: top;font-size: 17px"> ค่ากลยุทธ์ </th>
                                                <th style="vertical-align: top;font-size: 17px"> ค่าที่เกิดขึ้นจริง </th>
                                                <th style="vertical-align: top;font-size: 17px"> ระดับความสำเร็จ <br> (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="eval_data">
                                            @php
                                                $id = 0;
                                            @endphp
                                            @foreach ($data['eval'] as $row)
                                                <?php echo $id != $row->ID_MAST_GOAL ? "<tr style='background-color: #EBF5FB'><td colspan='5'><h4 style='font-weight: bold;line-height: 1.6em;'>" . $row->GOAL_NAME . "</h4></td></tr>" : '' ?>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="INDICATOR[]" value="{{ $row->ID_MAST_INDICATOR }}">
                                                        <h5 style="font-size: 16px;line-height: 1.6em;">{{ $row->INDICATOR_NAME }}</h5>
                                                    </td>
                                                    <td><input type="number" name="WEIGHT[]" class="form-control text-right" value="{{ isset($row->WEIGHT) ? $row->WEIGHT : 0 }}" autocomplete="off" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="3"></td>
                                                    <td><input type="text" class="form-control" name="GOAL[]" value="{{ isset($row->GOAL) ? $row->GOAL : '' }}" autocomplete="off"></td>
                                                    <td><input type="text" class="form-control" name="ASSESSMENT[]" value="{{ isset($row->ASSESSMENT) ? $row->ASSESSMENT : 0 }}" autocomplete="off"></td>
                                                    <td><input type="number" class="form-control text-right" name="PERCENT_SUCCESS[]" value="{{ isset($row->PERCENT_SUCCESS) ? $row->PERCENT_SUCCESS : '' }}" autocomplete="off" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength="3"></td>
                                                </tr>
                                                @php
                                                    $id = $row->ID_MAST_GOAL;
                                                @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td class="text-right" colspan="6">
                                                    <input type="submit" class="btn btn-primary" value="บันทึก">
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
        th {
            text-align: center;
        }
    </style>
@endpush
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script>
        $(document).ready(function() {

            $('#form-eval').submit((event) => {
                var total_weight = 0;
                $('input[name^="WEIGHT[]"]').each(function() {
                    total_weight = total_weight + parseFloat($(this).val());
                });
                if (total_weight < 100) {
                    event.preventDefault();
                    Swal.fire('น้ำหนักน้อยกว่า 100')
                } else if (total_weight > 100) {
                    event.preventDefault();
                    Swal.fire('น้ำหนักมากกว่า 100')
                }
            })

            $('#subject').on('change', function() {
                var subject_id = $(this).val();
                var year = $('#dev_year').val();
                $.ajax({
                    url: "{{ route('eval.indicator.eval') }}",
                    method: 'GET',
                    data: {subject_id:subject_id,year:year},
                    success: function(result) {
                        $('#eval_data').html(result)
                    }
                })
            })
            $('#data_year').val($('#dev_year').val());
            $('#dev_year').change(() => {
                $('#data_year').val($('#dev_year').val());
                var subject_id = $('#subject').val();
                var year = $('#dev_year').val();
                $.ajax({
                    url: "{{ route('eval.indicator.eval') }}",
                    method: 'GET',
                    data: {subject_id:subject_id,year:year},
                    success: function(result) {
                        $('#eval_data').html(result)
                    }
                })
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
