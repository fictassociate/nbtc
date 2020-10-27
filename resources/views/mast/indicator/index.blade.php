@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
    <div class="page-content-wrapper">
        <div class="page-content">
          <ul class="page-breadcrumb breadcrumb">
            <li>
              <i class="icon-compass" style="font-size: 18px;"></i>
              <a href="{{ route('indicator.index') }}" class="breadcrumb-link">ตัวชี้วัด</a>
            </li>
          </ul>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>ยุทธศาสตร์</h4>
                                    <div class="input-group">
                                        <select class="select2 form-control" id="subject">
                                            @foreach ($data['MastSubject'] as $row)
                                                <option value="{{ $row->ID_MAST_SUBJECT }}">{{ $row->SUBJECT_NAME }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h4>กลยุทธ์</h4>
                                    <div class="input-group">
                                        <select class="select2 form-control" id="goal">
                                            @foreach ($data['MastGoal'] as $row)
                                                <option value="{{ $row->ID_MAST_GOAL }}">{{ $row->GOAL_NAME }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (Session::has('success') || Session::has('error'))
            <div class="note note-{{ Session::has('success') ? 'success' : 'danger' }}">
                    <p> {{ Session::has('success') ? Session::get('success') : Session::get('error') }} </p>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div>
                            <a href="{{ route('indicator.create') }}" class="btn green"> เพิ่ม
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-checkable table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="80%" style="text-align: left;padding-left: 10px"> ตัวชี้วัด </th>
                                        <th width="5%"> แก้ไข </th>
                                        <th width="5%"> ลบ </th>
                                    </tr>
                                </thead>
                                <tbody id="goal_data">
                                    @foreach ($data['MastIndicator'] as $row)
                                    <tr>
                                        <td style="text-align: left;padding-left: 10px"> {{ $row->INDICATOR_NAME }} </td>
                                        <td class="text-center"> <a href="{{ route('indicator.edit', ['id'=>$row->ID_MAST_INDICATOR]) }}"><i class="icon-note"></i></a> </td>
                                        <td class="text-center"> <a href="{{ route('indicator.destroy', ['id'=>$row->ID_MAST_INDICATOR]) }}" onclick="return confirm('ท่านต้องการลบ {{ $row->INDICATOR_NAME }} ใช่หรือไม่')"><i class="icon-trash"></i></a> </td>
                                    </tr>
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
@push('script')
    <script>
        $(document).ready(function() {
            $('#plan').change(function() {
                var dev_id = $(this).val();
                $.ajax({
                    url: "{{ route('indicator.select.subject') }}",
                    method: 'GET',
                    data: {dev_id:dev_id},
                    success: function(result) {
                        $('#subject').html(result);
                    }
                })

                $.ajax({
                    url: "{{ route('indicator.select.goal') }}",
                    method: 'GET',
                    data: {dev_id:dev_id},
                    success: function(result) {
                        $('#goal').html(result);
                    }
                })

                $.ajax({
                    url: "{{ route('indicator.data.indicator') }}",
                    method: 'GET',
                    data: {dev_id:dev_id},
                    success: function(result) {
                        $('#goal_data').html(result);
                    }
                })
            })

            $('#subject').change(function() {
                var subject_id = $(this).val();
                $.ajax({
                    url: "{{ route('indicator.select.goal') }}",
                    method: 'GET',
                    data: {subject_id:subject_id},
                    success: function(result) {
                        $('#goal').html(result);
                    }
                })

                $.ajax({
                    url: "{{ route('indicator.data.indicator') }}",
                    method: 'GET',
                    data: {subject_id:subject_id},
                    success: function(result) {
                        $('#goal_data').html(result);
                    }
                })
            })

            $('#goal').change(function() {
                var goal_id = $(this).val();
                $.ajax({
                    url: "{{ route('indicator.data.indicator') }}",
                    method: 'GET',
                    data: {goal_id:goal_id},
                    success: function(result) {
                        $('#goal_data').html(result);
                    }
                })

                $.ajax({
                    url: "{{ route('indicator.data.indicator') }}",
                    method: 'GET',
                    data: {goal_id:goal_id},
                    success: function(result) {
                        $('#goal_data').html(result);
                    }
                })
            })
        })
    </script>
@endpush
