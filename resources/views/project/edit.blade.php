@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
  <div class="page-content-wrapper">
    <div class="page-content">
      <ul class="page-breadcrumb breadcrumb">
        <li>
          <i class="icon-social-dropbox" style="font-size: 18px;"></i>
          <a href="{{ route('project.index') }}" class="breadcrumb-link">โครงการทั้งหมด</a>
          <span class="next"> > </span>
        </li>
        <li>
          <span class="breadcrumb-current">
            แก้ไขโครงการ
          </span>
        </li>
      </ul>
      @if (Session::has('success') || Session::has('error'))
        <div class="note note-{{ Session::has('success') ? 'success' : 'danger' }}">
          <p> {{ Session::has('success') ? Session::get('success') : Session::get('error') }} </p>
        </div>
      @endif
      <div class="row">
        <div class="col-md-12">
          <div class="portlet light bordered">
            <div class="portlet-body form">
              <!-- BEGIN FORM-->
              <form action="{{ route('project.update', ['id'=>$data['project']->PROJECT_CODE]) }}" id="project-form" class="horizontal-form" method="POST">
                @csrf
                <div class="form-body">
                  <div class="portlet">
                    <div class="portlet-body">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group {{ Session::has('data') ? 'has-error' : '' }}">
                            <h5>รหัสโครงการ</h5>
                            <input type="text" class="form-control" name="PROJECT_CODE" readonly placeholder="กรอกรหัสโครงการ " value="{{ $data['project']->PROJECT_CODE }}">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <h5>ชื่อโครงการ</h5>
                            <input type="text" class="form-control" name="PROJECT_NAME" placeholder="กรอกชื่อโครงการ " value="{{ $data['project']->PROJECT_NAME }}" required>
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
                                  <option value="{{ $department->DEPARTMENT_CODE }}" {{ $data['department_code'] == $department->DEPARTMENT_CODE ? 'selected' : '' }}>{{ $department->DEPARTMENT_NAME }}</option>
                                @endforeach
                              </select>
                            @else
                              <input type="text" class="form-control" value="{{ Session::get('department') }}" readonly>
                            @endif
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <h5>ระยะเวลาดำเนินการ</h5>
                            <div class="input-group input-large date-picker input-daterange" data-date="01/11/2021" data-date-format="dd/mm/yyyy">
                              <input type="text" class="form-control" name="DT_START" autocomplete="off" value="{{ date('d/m/Y', strtotime($data['project']->DT_START)) }}">
                              <span class="input-group-addon"> ถึง </span>
                              <input type="text" class="form-control" name="DT_END" autocomplete="off" value="{{ date('d/m/Y', strtotime($data['project']->DT_END)) }}">
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
                                      <input type="checkbox" rel-section="{{ $i }}" class="subject subject-section-{{ $i }}" name="ID_MAST_SUBJECT[]" value="{{ $subject->ID_MAST_SUBJECT }}" {{ in_array($subject->ID_MAST_SUBJECT, $data['result']['ID_MAST_SUBJECT']) ? 'checked' : '' }}> {{ $subject->SUBJECT_NAME }}
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
                                              <input type="checkbox" rel-section="{{ $a }}" class="goal goal-section-{{ $a }}" name="ID_MAST_GOAL[]" value="{{ $goal->ID_MAST_GOAL }}" {{ in_array($goal->ID_MAST_GOAL, $data['result']['ID_MAST_GOAL']) ? 'checked' : '' }}> {{ $goal->GOAL_NAME }}
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
                                                      <input type="checkbox" name="ID_MAST_INDICATOR[]" value="{{ $indicator->ID_MAST_INDICATOR }}" {{ in_array($indicator->ID_MAST_INDICATOR, $data['result']['ID_MAST_INDICATOR']) ? 'checked' : '' }}> {{ $indicator->INDICATOR_NAME }}
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
                            <textarea cols="30" rows="5" class="form-control" name="SUMMARY_PROCESS">{{$data['project']->SUMMARY_PROCESS}}</textarea>
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <h5>สถานะการดำเนินงานโครงการ</h5>
                      <div class="form-group">
                        <div class="d-flex">
                          <label class="mt-checkbox mt-checkbox-outline">
                            <input
                            type="checkbox"
                            class="project_status check_1"
                            name="project_status[0]"
                            value="1"
                            <?php echo @$data['status1']->STATUS == 1 ? "checked" : "" ?>
                            />&nbsp;
                            <span></span>
                          </label>&nbsp;
                          <label>เริ่มดำเนินการ</label>&nbsp;
                          <label class="step_1">จะเริ่มดำเนินการ</label>&nbsp;
                          <div class="step_1 input-group input-small date date-picker" data-date-format="dd-mm-yyyy">
                            <span class="input-group-btn">
                              <button class="btn default btn-sm n-border-bg btn-date" type="button">
                                <i class="fa fa-calendar"></i>
                              </button>
                            </span>
                            <input
                            type="text"
                            name="statusDate[0]"
                            value="<?php echo @$data['status1']->STATUS == 1
                            ?
                            date('d/m/Y', strtotime(@$data['status1']->CREATED_AT)) : '' ?>"
                            class="n-border-bg bt-border size-date req-1"
                            />
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="d-flex">
                          <label class="mt-checkbox mt-checkbox-outline">
                            <input
                            type="checkbox"
                            class="project_status check_2"
                            name="project_status[1]"
                            <?php echo @$data['status2']->STATUS == 2 ? "checked" : "" ?>
                            value="2"
                            />&nbsp;
                            <span></span>
                          </label>&nbsp;
                          <label>กำลังดำเนินการ</label>&nbsp;
                          <label class="step_2">คาดว่าจะแล้วเสร็จ</label>&nbsp;
                          <div class="step_2 input-group input-small date date-picker" data-date-format="dd-mm-yyyy">
                            <span class="input-group-btn">
                              <button class="btn default btn-sm n-border-bg btn-date" type="button">
                                <i class="fa fa-calendar"></i>
                              </button>
                            </span>
                            <input
                            type="text"
                            name="statusDate[1]"
                            class="n-border-bg bt-border size-date req-2"
                            value="<?php echo @$data['status2']->STATUS == 2
                            ?
                            date('d/m/Y', strtotime(@$data['status2']->CREATED_AT)) : '' ?>"
                            />
                          </div>
                        </div>
                        <?php
                          $data2 = json_decode(@$data['status2']->DETAIL);
                        ?>
                        <div class="step_2">
                          <blockquote class="font-size-bq">
                            <h5 class="title-bq">ความก้าวหน้าของโครงการกรณีโครงการ "อยู่ระหว่างดำเนินการ"</h5>
                            <ol>
                              <li>
                                ความก้าวหน้าของงานเมื่อเทียบกับปริมาณงานทั้งหมดที่ต้องดำเนินการ มีความก้าวหน้าร้อยละ
                                <input
                                type="text"
                                name="text21"
                                value="<?php echo @$data2->text21 ? $data2->text21 : '' ?>"
                                class="n-border-bg bt-border size-date req-2"
                                />
                              </li>
                              <li>
                                ความก้าวหน้าของงานเมื่อเทียบกับแผน พบว่าการดำเนินงาน
                                <div class="form-group">
                                  <input
                                  type="radio"
                                  name="sec_2_2"
                                  class="sec_2_2 req-2"
                                  value="1"
                                  <?php echo @$data2->text22->status == 1 ? "checked" : '' ?>
                                  /> เป็นไปตามแผน
                                </div>
                                <div class="form-group">
                                  <input
                                  type="radio"
                                  name="sec_2_2"
                                  class="sec_2_2 req-2"
                                  value="2"
                                  <?php echo @$data2->text22->status == 2 ? "checked" : '' ?>
                                  />
                                  เร็วกว่าแผนร้อยละ
                                  <input
                                  type="text"
                                  name="text22"
                                  class="sec_2_text_2 n-border-bg bt-border size-date"
                                  value="<?php echo @$data2->text22->status == 2 ? $data2->text22->value : '' ?>"
                                  />
                                </div>
                                <div class="form-group">
                                  <input
                                  type="radio"
                                  name="sec_2_2"
                                  class="sec_2_2 req-2"
                                  value="3"
                                  <?php echo @$data2->text22->status == 3 ? "checked" : '' ?>
                                  />
                                  ช้ากว่าแผนร้อยละ
                                  <input
                                  type="text"
                                  name="text23"
                                  class="sec_2_text_3 n-border-bg bt-border size-date"
                                  value="<?php echo @$data2->text22->status == 3 ? $data2->text22->value : '' ?>"
                                  />
                                </div>
                              </li>
                              <li>
                                ความก้าวหน้าในการเบิกจ่ายเมื่อเทียบกับงบประมาณที่ต้องจ่ายจริงทั้งหมด มีความก้าวหน้าร้อยละ
                                <input
                                type="text"
                                name="text24"
                                class="n-border-bg bt-border size-date"
                                value="<?php echo @$data2->text24 ? @$data2->text24 : '' ?>"
                                />
                              </li>
                              <li>
                                ความก้าวหน้าของงานเมื่อเทียบกับแผนการเบิกจ่าย พบว่าการเบิกจ่ายงบประมาณ
                                <div class="form-group">
                                  <input
                                  type="radio"
                                  name="sec_2_4"
                                  class="sec_2_4"
                                  value="1"
                                  <?php echo @$data2->text25->status == 1 ? "checked" : '' ?>
                                  />
                                  เป็นไปตามแผน
                                </div>
                                <div class="form-group">
                                  <input
                                  type="radio"
                                  name="sec_2_4"
                                  class="sec_2_4"
                                  value="2"
                                  <?php echo @$data2->text25->status == 2 ? "checked" : '' ?>
                                  />
                                  เร็วกว่าแผนร้อยละ
                                  <input
                                  type="text"
                                  name="text25"
                                  class="sec_2_4_2 n-border-bg bt-border size-date"
                                  value="<?php echo @$data2->text25->status == 2 ? $data2->text25->value : '' ?>"
                                  />
                                </div>
                                <div class="form-group">
                                  <input
                                  type="radio"
                                  name="sec_2_4"
                                  class="sec_2_4"
                                  value="3"
                                  <?php echo @$data2->text25->status == 3 ? "checked" : '' ?>
                                  />
                                  ช้ากว่าแผนร้อยละ
                                  <input
                                  type="text"
                                  name="text26"
                                  class="sec_2_4_3 n-border-bg bt-border size-date"
                                  value="<?php echo @$data2->text25->status == 3 ? $data2->text25->value : '' ?>"
                                  />
                                </div>
                              </li>
                            </ol>
                          </blockquote>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="d-flex">
                          <label class="mt-checkbox mt-checkbox-outline">
                            <input
                            type="checkbox"
                            class="project_status check_3"
                            name="project_status[2]"
                            value="3"
                            <?php echo @$data['status3']->STATUS == 3 ? "checked" : "" ?>
                            />&nbsp;
                            <span></span>
                          </label>&nbsp;
                          <label>ดำเนินการแล้วเสร็จ</label>&nbsp;
                          <label class="step_3">วันที่แล้วเสร็จ</label>&nbsp;
                          <div class="step_3 input-group input-small date date-picker" data-date-format="dd-mm-yyyy">
                            <span class="input-group-btn">
                              <button class="btn default btn-sm n-border-bg btn-date" type="button">
                                <i class="fa fa-calendar"></i>
                              </button>
                            </span>
                            <input
                            type="text"
                            name="statusDate[2]"
                            class="n-border-bg bt-border size-date req-3"
                            readonly
                            value="<?php echo @$data['status3']->STATUS == 3
                            ?
                            date('d/m/Y', strtotime(@$data['status3']->CREATED_AT)) : '' ?>"
                            />
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="mt-checkbox mt-checkbox-outline">
                          <input
                          type="checkbox"
                          class="project_status check_4"
                          name="project_status[4]"
                          value="4"
                          <?php echo @$data['status4']->STATUS == 4 ? "checked" : "" ?>
                          />
                          <span></span>
                        </label>&nbsp;
                        <label>ยกเลิกการดำเนินการ</label>
                        <?php
                          $data4 = json_decode(@$data['status4']->DETAIL);
                        ?>
                        <div class="step_4">
                          <blockquote class="font-size-bq">
                            <h5>เนื่องจาก</h5>
                            <textarea name="text41" class="form-control req-4"><?php echo @$data4->detail; ?></textarea>
                          </blockquote>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
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
                          @foreach ($data['budget'] as $budget)
                            <tr>
                              <td class="text-center">
                                <input type="text" class="form-control check-number" name="YEAR[]" value="{{ @$budget->BUDGET_YEAR }}">
                              </td>
                              <td><input type="number" class="form-control text-right" name="BUDGET2[]" value="{{ @$budget->BUDGET2 }}"></td>
                              <td><input type="number" class="form-control text-right" name="BUDGET[]" value="{{ @$budget->BUDGET }}"></td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="row">
                      <div class="col-md-12">
                          @if (Session::get('username') == $data['project']->USER_LOGIN || Session::get('role') == "S")
                              <a href="{{ route('project.result', ['id'=>$data['project']->PROJECT_CODE]) }}" class="btn blue"> เพิ่ม / แก้ไขผลผลิต</a>
                          @endif
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                              <br />
                              <h5>ผลผลิต</h5>
                              {{ !$data['project']->OUTCOME ? "ยังไม่มีผลผลิต" : $data['project']->OUTCOME }}<br><br>
                              <h5>ผลลัพธ์ขั้นต้น</h5>
                              <table class="table table-bordered">
                                  <thead>
                                      <tr>
                                          <th width="5%" class="text-center" style="vertical-align: middle">ปี</th>
                                          <th width="5%" class="text-center" style="vertical-align: middle">ไตรมาส</th>
                                          <th width="35%" class="text-center" style="vertical-align: middle">ผลลัพธ์ขั้นต้น</th>
                                          <th width="10%" class="text-center" style="vertical-align: middle">ค่ากลยุทธ์</th>
                                          <th width="10%" class="text-center" style="vertical-align: middle">ผลการดำเนินงานจริง</th>
                                          <th width="30%" class="text-center" style="vertical-align: middle">ตัวชี้วัด</th>
                                          @if (Session::get('username') == $data['project']->USER_LOGIN)
                                              <th width="5%" class="text-center" style="vertical-align: middle">จัดการ</th>
                                          @endif
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @if (is_array($data['begin']))
                                          @foreach ($data['begin'] as $b)
                                              <tr>
                                                  <td class="text-center">{{ $b['YEAR'] }}</td>
                                                  <td class="text-center">{{ $b['QUARTER'] }}</td>
                                                  <td>{{ $b['BEGIN_RESULT'] }}</td>
                                                  <td class="text-center">{{ $b['GOAL'] }}</td>
                                                  <td class="text-center">{{ $b['ACTUAL'] }}</td>
                                                  <td>
                                                      @php
                                                          $i = 1;
                                                      @endphp
                                                      @foreach ($b['INDICATOR_NAME'] as $name)
                                                          {{ $i }}. {{ $name }} <br>
                                                          @php
                                                              $i++;
                                                          @endphp
                                                      @endforeach
                                                  </td>
                                                  @if (Session::get('username') == $data['project']->USER_LOGIN)
                                                      <td class="text-center"><a href="{{ route('project.begin.destroy', ['id'=>$b['ID_PROJECT_BEGIN_RESULT']]) }}" onclick="return confirm('ต้องการลบผลลัพธ์ใช่หรือไม่')"><i class="icon-trash"></i></a></td>
                                                  @endif
                                              </tr>
                                          @endforeach
                                      @endif
                                  </tbody>
                              </table>

                              <h5>ผลลัพธ์ขั้นกลาง</h5>
                              <table class="table table-bordered">
                                  <thead>
                                      <tr>
                                          <th width="5%" class="text-center" style="vertical-align: middle">ปี</th>
                                          <th width="5%" class="text-center" style="vertical-align: middle">ไตรมาส</th>
                                          <th width="35%" class="text-center" style="vertical-align: middle">ผลลัพธ์ขั้นกลาง</th>
                                          <th width="10%" class="text-center" style="vertical-align: middle">ค่ากลยุทธ์</th>
                                          <th width="10%" class="text-center" style="vertical-align: middle">ผลการดำเนินงานจริง</th>
                                          <th width="30%" class="text-center" style="vertical-align: middle">ตัวชี้วัด</th>
                                          @if (Session::get('username') == $data['project']->USER_LOGIN)
                                              <th width="5%" class="text-center" style="vertical-align: middle">จัดการ</th>
                                          @endif
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @if (is_array($data['mid']))
                                          @foreach ($data['mid'] as $b)
                                              <tr>
                                                  <td class="text-center">{{ $b['YEAR'] }}</td>
                                                  <td class="text-center">{{ $b['QUARTER'] }}</td>
                                                  <td>{{ $b['MID_RESULT'] }}</td>
                                                  <td class="text-center">{{ $b['GOAL'] }}</td>
                                                  <td class="text-center">{{ $b['ACTUAL'] }}</td>
                                                  <td>
                                                      @php
                                                          $i = 1;
                                                      @endphp
                                                      @foreach ($b['INDICATOR_NAME'] as $name)
                                                          {{ $i }}. {{ $name }} <br>
                                                          @php
                                                              $i++;
                                                          @endphp
                                                      @endforeach
                                                  </td>
                                                  @if (Session::get('username') == $data['project']->USER_LOGIN)
                                                      <td class="text-center"><a href="{{ route('project.mid.destroy', ['id'=>$b['ID_PROJECT_MID_RESULT']]) }}" onclick="return confirm('ต้องการลบผลลัพธ์ใช่หรือไม่')"><i class="icon-trash"></i></a></td>
                                                  @endif
                                              </tr>
                                          @endforeach
                                      @endif
                                  </tbody>
                              </table>

                              <h5>ผลลัพธ์ขั้นปลาย</h5>
                              <table class="table table-bordered">
                                  <thead>
                                      <tr>
                                          <th width="5%" class="text-center" style="vertical-align: middle">ปี</th>
                                          <th width="5%" class="text-center" style="vertical-align: middle">ไตรมาส</th>
                                          <th width="35%" class="text-center" style="vertical-align: middle">ผลลัพธ์ขั้นปลาย</th>
                                          <th width="10%" class="text-center" style="vertical-align: middle">ค่ากลยุทธ์</th>
                                          <th width="10%" class="text-center" style="vertical-align: middle">ผลการดำเนินงานจริง</th>
                                          <th width="30%" class="text-center" style="vertical-align: middle">ตัวชี้วัด</th>
                                          @if (Session::get('username') == $data['project']->USER_LOGIN)
                                              <th width="5%" class="text-center" style="vertical-align: middle">จัดการ</th>
                                          @endif
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @if (is_array($data['end']))
                                          @foreach ($data['end'] as $b)
                                              <tr>
                                                  <td class="text-center">{{ $b['YEAR'] }}</td>
                                                  <td class="text-center">{{ $b['QUARTER'] }}</td>
                                                  <td>{{ $b['END_RESULT'] }}</td>
                                                  <td class="text-center">{{ $b['GOAL'] }}</td>
                                                  <td class="text-center">{{ $b['ACTUAL'] }}</td>
                                                  <td>
                                                      @php
                                                          $i = 1;
                                                      @endphp
                                                      @foreach ($b['INDICATOR_NAME'] as $name)
                                                          {{ $i }}. {{ $name }} <br>
                                                          @php
                                                              $i++;
                                                          @endphp
                                                      @endforeach
                                                  </td>
                                                  @if (Session::get('username') == $data['project']->USER_LOGIN)
                                                      <td class="text-center"><a href="{{ route('project.end.destroy', ['id'=>$b['ID_PROJECT_END_RESULT']]) }}" onclick="return confirm('ต้องการลบผลลัพธ์ใช่หรือไม่')"><i class="icon-trash"></i></a></td>
                                                  @endif
                                              </tr>
                                          @endforeach
                                      @endif
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <h5>ปัญหา อุปสรรคในการดำเนินงาน</h5>
                        <textarea name="PROBLEM" class="form-control">{{ $data['project']->PROBLEM }}</textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <h5>วิธีการแก้ไขที่ผ่านมา</h5>
                        <textarea id="" name="OBJECTIVE" class="form-control">{{ $data['project']->OBJECTIVE }}</textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <h5>ข้อเสนอแนะเพื่อปรับปรุงและดำเนินงานในระยะต่อไป</h5>
                        <textarea name="SUGGESTION" id="" class="form-control">{{ $data['project']->SUGGESTION }}</textarea>
                      </div>
                    </div>
                  </div>
                  <div class="form-actions text-center">
                    @if (Session::get('username') == $data['project']->USER_LOGIN || Session::get('role') == "S")
                      <button type="submit" class="btn blue"> บันทึก</button>
                    @endif
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
@push('style')
  <style>
    ol {
      margin-left: -20px;
    }
    .form .form-body {
      padding-top: 0px;
    }

    ul {list-style-type: none;}

    h1, h2, h3, h4, h5, h6 {
      font-weight: 700
    }

    .d-flex {
      display: flex;
    }

    .text-form {
      border: 1px solid #C2CAD8;
      text-align: center;
      width: 50px;
    }

    .n-border-bg {
      border: none !important;
      background: none !important;
    }

    .bt-border {
      border-bottom: 1px solid #C2CAD8 !important;
    }

    .btn-date {
      margin-top: -4px;
    }

    .size-date {
      width: 85px;
      text-align: center;
    }

    .background-gray {
      background: #EEF1F5 !important;
    }

    .font-size-bq {
      font-size: 14px;
    }

    .title-bq {
      font-size: 15px;
    }
  </style>
@endpush
@push('script')
  <script>
    $(document).ready(function() {
      function hideStep() {
        for (var i = 1;i <= 4;i++) {
          if ($(".check_"+i).is(":not(:checked)")) {
            $(".step_"+i).hide();
            $(".req-"+i).prop('required', false);
          } else {
            $(".step_"+i).show();
            $(".req-"+i).prop('required', true);
          }
        }
      }

      function disableSec2() {
        $("input[class^='sec_2_text_']").prop('disabled', true);
        $("input[class^='sec_2_text_']").prop('required', false);
      }

      function disableSec24() {
        $("input[class^='sec_2_4_']").prop('disabled', true);
        $("input[class^='sec_2_4_']").prop('required', false);
      }

      hideStep();
      $('.project_status').click(function() {
        hideStep();
      })

      disableSec2();
      $('.sec_2_2').click(function() {
        var sec = $(this).val();
        disableSec2();
        $('.sec_2_text_' + sec).removeAttr('disabled');
        $("input[class^='sec_2_text_']").val('');
        $(".sec_2_text_" + sec).prop('required', true);
      });

      disableSec24();
      $('.sec_2_4').click(function() {
        var sec = $(this).val();
        disableSec24();
        $('.sec_2_4_' + sec).removeAttr('disabled');
        $("input[class^='sec_2_4_']").val('');
      });

      $('#add_year').click(() => {
        $('#show_year')
        .append("<tr><td><input type='text' name='YEAR[]' \
        class='form-control'></td><td><input type='text' name='BUDGET2[]' \
        class='form-control'></td><td><input type='text' name='BUDGET[]' \
        class='form-control'></td></tr>");
      })

      @php
        $i = 1;
        $a = 1;
      @endphp
      @foreach ($data['MastSubject'] as $subject)
        if($('.subject-section-' + {{ $i }}).is(":checked")){
          $('.goal-area-' + {{ $i }}).show();
        } else {
          $('.goal-area-' + {{ $i }}).hide();
        }
        @foreach ($data['MastGoal'] as $goal)
          if($('.goal-section-' + {{ $a }}).is(":checked")){
            $('.indicator-area-' + {{ $a }}).show();
          } else {
            $('.indicator-area-' + {{ $a }}).hide();
          }
          @php
            $a++;
          @endphp
        @endforeach
        @php
          $i++;
        @endphp
      @endforeach
      // $("ul[class^='goal-area-']").hide();
      // $("ul[class^='indicator-area-']").hide();
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
