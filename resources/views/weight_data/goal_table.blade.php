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
        </li>
      </ul>
      @if (Session::has('error'))
        <div class="note note-danger">
          <p> {{Session::get('error')}} </p>
        </div>
      @endif
      <div class="row">
        <div class="col-md-12">
          <form action="" method="POST" id="form-eval">
            <div class="portlet light bordered">
              <input type="hidden" name="YEAR" id="data_year">
              <div class="portlet-body">
                <div class="table-scrollable">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th width="3%" class="text-center">#</th>
                        <th> ตัวชี้วัด </th>
                        <th width="10%"> % ความสำเร็จ</th>
                      </tr>
                    </thead>
                    <tbody id="eval_data">
                      <?php $a = 1; ?>
                      @foreach ($data as $row)
                        <tr style="background-color: #EBF5FB; font-weight: bold;">
                          <td colspan="5">
                            {{$row['goal_name']}}
                          </td>
                        </tr>
                        @if (@$row['indicator'])
                          @foreach ($row['indicator'] as $ind)
                            <tr>
                              <td class="text-center">
                                {{$a}}
                              </td>
                              <td>
                                <a href="{{ route('weight.indicator', ['intId'=>$ind['ind_id']]) }}">
                                  {{$ind['ind_name']}}
                                </a>
                              </td>
                              <td>
                                <span class="font-blue text-center" style="display: block;"> <b>{{round($ind['progress'], 2)}} %</b></span>
                              </td>
                            </tr>
                            <?php $a++; ?>
                          @endforeach
                        @endif
                      @endforeach

                      <tr>
                        <td class="text-right" colspan="8">
                          <span class="font-yellow-gold summary-percent-success"> <h4 style="margin-right: 20px">{{round($total,2)}} %</h4> </span>
                        </td>
                      </tr>
                    </tbody>
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
