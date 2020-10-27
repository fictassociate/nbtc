@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
    <div class="page-content-wrapper">
        <div class="page-content">
          <ul class="page-breadcrumb breadcrumb">
            <li>
              <i class="icon-eyeglasses" style="font-size: 18px;"></i>
              <a href="{{ route('subject.index') }}" class="breadcrumb-link">ยุทธศาสตร์</a>
            </li>
          </ul>
            {{-- <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>แผนยุทธศาสตร์</h4>
                                    <div class="input-group">
                                        <select class="select2 form-control" id="select_plan">
                                            @foreach ($data['plan'] as $row)
                                            <option value="{{ $row->ID_MAST_DEV }}">{{ $row->PLAN_NAME }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
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
                            <a href="{{ route('subject.create') }}" class="btn green"> เพิ่ม
                                <i class="fa fa-plus"></i>
                            </a>
                        </div>
                        <div class="portlet-body">
                            <table class="table table-striped table-checkable table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="80%" style="text-align: left;padding-left: 10px"> ยุทธศาสตร์ </th>
                                        <th width="5%"> แก้ไข </th>
                                        <th width="5%"> ลบ </th>
                                    </tr>
                                </thead>
                                <tbody id="data_subject">
                                    @foreach ($data['subject'] as $row)
                                    <tr>
                                        <td style="text-align: left;padding-left: 10px"> {{ $row->SUBJECT_NAME }} </td>
                                        <td class="text-center"> <a href="{{ route('subject.edit', ['id'=>$row->ID_MAST_SUBJECT]) }}"><i class="icon-note"></i></a> </td>
                                        <td class="text-center"> <a href="{{ route('subject.destroy', ['id'=>$row->ID_MAST_SUBJECT]) }}" onclick="return confirm('ท่านต้องการลบ {{ $row->SUBJECT_NAME }} ใช่หรือไม่')"><i class="icon-trash"></i></a> </td>
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
            $('#select_plan').change(function() {
                var plan_id = $(this).val();
                $.ajax({
                    url: "{{ route('subject.select.plan') }}",
                    method: 'GET',
                    data: {plan_id:plan_id},
                    success: function(result) {
                        $('#data_subject').html(result);
                    }
                })
            })
        })
    </script>
@endpush
