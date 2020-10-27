@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
    <div class="page-content-wrapper">
        <div class="page-content">
          <ul class="page-breadcrumb breadcrumb">
            <li>
              <i class="icon-social-dropbox" style="font-size: 18px;"></i>
              <a href="{{ route('project.index') }}" class="breadcrumb-link">โครงการทั้งหมด</a>
            </li>
          </ul>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body">
                            <table class="table table-striped table-checkable table-bordered table-hover" id="sample_1">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%"> ลำดับ. </th>
                                        <th class="text-center" width="10%"> รหัสโครงการ </th>
                                        <th class="text-center" width="30%"> ชื่อโครงการ / การดำเนินการ </th>
                                        <th class="text-center" width="15%"> หน่วยงาน </th>
                                        <th class="text-center" width="20%"> ระยะเวลาการดำเนินงาน </th>
                                        {{-- <th class="text-center" width="10%"> แผนยุทธศาสตร์ </th> --}}
                                        <th class="text-center" width="10%"> งบประมาณ </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($data['project'] as $row)
                                        <tr>
                                            <td>{{ $i }}</td>
                                            <td>{{ $row->PROJECT_CODE }}</td>
                                            <td><a href="{{ route('project.edit', ['id'=>$row->PROJECT_CODE]) }}">{{ $row->PROJECT_NAME }}</a></td>
                                            <td>{{ $row->DEPARTMENT_NAME }}</td>
                                            <td>{{ $row->DURATION }}</td>
                                            {{-- <td>
                                                @php
                                                    $ex = explode(',', $row->SUBJECT);
                                                    $i = 1;
                                                @endphp
                                                @foreach ($ex as $subject_id)
                                                    @foreach ($data['subject'] as $subject)
                                                        @if ($subject->ID_MAST_SUBJECT == $subject_id)
                                                            {{ $i }}. {{ mb_substr($subject->SUBJECT_NAME, 0, 10) }} <br>
                                                        @endif
                                                    @endforeach
                                                @endforeach
                                            </td> --}}
                                            <td>{{ number_format($row->BUDGET,2,".",",") }}</td>
                                        </tr>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
