@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
    <div class="page-content-wrapper">
        <div class="page-content">
          <ul class="page-breadcrumb breadcrumb">
            <li>
              <i class="icon-drop" style="font-size: 18px;"></i>
              <a href="{{ route('goal.index') }}" class="breadcrumb-link">กลยุทธ์</a>
            </li>
          </ul>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>ยุทธศาสตร์</h4>
                                    <div class="input-group">
                                        <select class="select2 form-control" id="subject">
                                            @foreach ($data['MastSubject'] as $row)
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
            {{-- <div class="note note-info">
                <p> A black page template with a minimal dependency assets to use as a base for any custom page you create </p>
            </div> --}}
            @if (Session::has('success') || Session::has('error'))
            <div class="note note-{{ Session::has('success') ? 'success' : 'danger' }}">
                    <p> {{ Session::has('success') ? Session::get('success') : Session::get('error') }} </p>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div>
                            <a href="{{ route('goal.create') }}" class="btn green"> เพิ่ม
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                        <div class="portlet-body" id="goal">
                            <table class="table table-striped table-checkable table-bordered table-hover" id="sample_1">
                                <thead>
                                    <tr>
                                        <th width="80%" style="text-align: left;padding-left: 10px"> กลยุทธ์ </th>
                                        <th width="5%"> แก้ไข </th>
                                        <th width="5%"> ลบ </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['goal'] as $row)
                                    <tr>
                                        <td style="text-align: left;padding-left: 10px"> {{ $row->GOAL_NAME }} </td>
                                        <td class="text-center"> <a href="{{ route('goal.edit', ['id'=>$row->ID_MAST_GOAL]) }}"><i class="icon-note"></i></a> </td>
                                        <td class="text-center"> <a href="{{ route('goal.destroy', ['id'=>$row->ID_MAST_GOAL]) }}" onclick="return confirm('ท่านต้องการลบ {{ $row->GOAL_NAME }} ใช่หรือไม่')"><i class="icon-trash"></i></a> </td>
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
            $('#subject').change(function() {
                var subject_id = $(this).val();
                $.ajax({
                    url: "{{ route('goal.select.goal') }}",
                    method: 'GET',
                    data: {subject_id:subject_id},
                    success: function(result) {
                        $('#goal').html(result);
                    }
                })
            })
        });
    </script>
@endpush
