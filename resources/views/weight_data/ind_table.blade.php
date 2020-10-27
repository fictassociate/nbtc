@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
  <div class="page-content-wrapper">
    <div class="page-content">
      <ul class="page-breadcrumb breadcrumb">
        <li>
          <i class="icon-screen-desktop" style="font-size: 18px;"></i>
          <a href="{{ route('weight.subject') }}" class="breadcrumb-link">Dashboard</a>
          <span class="next"> > </span>
        </li>
        <li>
          <label class="label label-xs bg-green ">ประเด็น</label>
          <a href="{{ route('weight.goal', ['subjectId'=>$subject->ID_MAST_SUBJECT]) }}" class="breadcrumb-link">
            {{$subject->SUBJECT_NAME}}
          </a>
          <span class="next"> > </span>
        </li>
        <li>
          <label class="label label-xs bg-blue ">ตัวชี้วัด</label>
          <span class="breadcrumb-current">
            {{$subject->INDICATOR_NAME}}
          </span>
        </li>
      </ul>
      <div class="row">
        <div class="col-md-12">
          <div class="portlet light bordered">
            <div class="portlet-body form">
              <div class="row">
                <form method="GET">
                  <div class="col-md-5">
                    <h4>ปีงบประมาณ</h4>
                    <div class="input-group">
                      <select name="year" class="form-control select2" id="issue">
                        <option value="" disabled selected>SELECT</option>
                        @foreach ($years as $year)
                          <option value="{{ $year->YEAR }}" {{@$_GET['year'] == $year->YEAR || (date('Y') + 543) == $year->YEAR ? 'selected':''}}>{{ $year->YEAR }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <h4>ไตรมาส</h4>
                    <div class="input-group">
                      <select name="quarter" class="select2 form-control" id="indicator">
                        <option value="1" {{@$_GET['quarter'] == 1 ? 'selected':''}}>1</option>
                        <option value="2" {{@$_GET['quarter'] == 2 ? 'selected':''}}>2</option>
                        <option value="3" {{@$_GET['quarter'] == 3 ? 'selected':''}}>3</option>
                        <option value="4" {{@$_GET['quarter'] == 4 || !@$_GET['quarter'] ? 'selected':''}}>4</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <h4> &nbsp; </h4>
                    <input type="submit" class="btn btn-info" id="lookup" value="ดูข้อมูล">
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <form action="{{ route('weight.store') }}" method="POST" id="form-eval" id="form-eval">
            @csrf
            <input type="hidden" name="ID_MAST_INDICATOR" value="{{$indId}}" />
            <div class="portlet light bordered">
              <input type="hidden" name="YEAR" id="data_year">
              <div class="portlet-body">
                <div class="table-scrollable">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="2%" class="text-center">#</th>
                        <th width="2%" class="text-center">No</th>
                        <th width="20%"> โครงการ </th>
                        <th width="20%"> ผลลัพธ์ขั้นต้น </th>
                        <th width="20%"> ผลลัพธ์ขั้นกลาง </th>
                        <th width="20%"> ผลลัพธ์ขั้นปลาย </th>
                        <th width="7%" style="font:14px;"> น้ำหนัก</th>
                        <th width="8%" style="font:14px;"> % สำเร็จ</th>
                        <th width="7%" style="font:14px;"> % ก้าวหน้า</th>
                      </tr>
                    </thead>
                      <tbody id="eval_data">
                        <?php
                          $i = 1;
                          $sum = 0;
                          $amount = 0;
                        ?>
                        @foreach ($projects as $project)
                          <tr>
                            <td align="center">
                              <div class="mt-checkbox-list" style="margin-left: 10px;">
                                <label class="mt-checkbox mt-checkbox-outline">
                                  <input type="checkbox" name="STATUS[]" value="1" {{@$project['DATA']->STATUS ? 'checked':''}}>
                                  <span></span>
                                </label>
                              </div>
                            </td>
                            <td class="text-center">
                              {{$i}}
                            </td>
                            <td class="font-blue">
                              <a href="{{ route('project.edit', ['id'=>$project['PROJECT_CODE']]) }}" target="_blank">{{$project['PROJECT_CODE']}} - {{$project['PROJECT_NAME']}}</a>
                              <input type="hidden" name="PROJECT_CODE[]" value="{{$project['PROJECT_CODE']}}" />
                              <br>
                              <span class="font-grey-gallery">- {{$project['DEPARTMENT_NAME']}}</span>
                            </td>
                            <td>
                              @if (@$project['BEGIN']->BEGIN_RESULT)
                                {{@$project['BEGIN']->BEGIN_RESULT}}<br>
                                <span class="font-yellow-gold">ค่าเป้าหมาย</span> : {{number_format(@$project['BEGIN']->GOAL,0,'.',',')}} <br/>
                                <span class="font-blue">ค่าที่เกิดขึ้นจริง</span> : {{number_format(@$project['BEGIN']->ACTUAL,0,'.',',')}}
                              @endif
                            </td>
                            <td>
                              @if (@$project['MID']->MID_RESULT)
                                {{@$project['MID']->MID_RESULT}}<br>
                                <span class="font-yellow-gold">ค่าเป้าหมาย</span> : {{number_format(@$project['MID']->GOAL,0,'.',',')}} <br/>
                                <span class="font-blue">ค่าที่เกิดขึ้นจริง</span> : {{number_format(@$project['MID']->ACTUAL,0,'.',',')}}
                              @endif
                            </td>
                            <td>
                              @if (@$project['END']->END_RESULT)
                                {{@$project['END']->END_RESULT}}<br>
                                <span class="font-yellow-gold">ค่าเป้าหมาย</span> : {{number_format(@$project['END']->GOAL,0,'.',',')}} <br/>
                                <span class="font-blue">ค่าที่เกิดขึ้นจริง</span> : {{number_format(@$project['END']->ACTUAL,0,'.',',')}}
                              @endif
                            </td>
                            <td>
                              <input
                              type="text"
                              class="form-control"
                              name="WEIGHT[]"
                              value="{{@$project['DATA']->WEIGHT}}"
                              autocomplete="off"
                              oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                              maxlength="3"
                              >
                            </td>
                            <td>
                              <input
                              type="text"
                              class="form-control text-right"
                              name="SUCCESS[]" value="{{@$project['DATA']->SUCCESS}}"
                              autocomplete="off"
                              oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                              maxlength="3"
                              id="success_{{$i}}"
                              >
                            </td>
                            <td align="center">
                              <span class="font-green text-center percent-success"><b>{{@$project['DATA']->WEIGHT * @$project['DATA']->SUCCESS}} %</b></span>
                            </td>
                          </tr>
                          <?php
                            if (@$project['DATA']->STATUS) {
                              $amount += @$project['DATA']->STATUS;
                              $sum += @$project['DATA']->WEIGHT * @$project['DATA']->SUCCESS;
                            }

                            $i++;
                          ?>
                        @endforeach
                        <tr>
                          <td class="text-right" colspan="9">
                            <span class="font-yellow-gold summary-percent-success"> <h4 style="margin-right: 10px">{{!(@$sum || @$amount) == 0 ? @$sum : 0}} %</h4> &nbsp;&nbsp;</span>
                          </td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td class="text-right" colspan="9">
                            <input type="submit" class="btn btn-primary" id="save_btn" value="บันทึก">
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
    h1, h2, h3, h4, h5, h6 {
      font-weight: 700
    }
  </style>
@endpush
@push('script')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script>
    $(document).ready(function() {
      $('#form-eval').submit((event) => {
        var i = 1;
        var total_weight = 0;
        $('input[name^="WEIGHT[]"]').each(function() {
          var success = $('#success_'+i).val();
          if (success > 100) {
              event.preventDefault();
              Swal.fire('ความสำเร็จต้องต่ำกว่าหรือเท่ากับ 100')
              $('#success_'+i).focus();
          }

          total_weight = total_weight + parseFloat($(this).val());
          if (total_weight > 1) {
              event.preventDefault();
              Swal.fire('น้ำหนักรวมมากกว่า 1')
          }

          i++
        });
      })
    });
  </script>
@endpush
