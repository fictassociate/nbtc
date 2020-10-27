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
                                <div class="form-body">
                                    <div class="portlet">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-file"></i>Import Excel
                                            </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                </div>
                                                <div class="col-md-6">
                                                    <br>
                                                    <form action="{{ route('excel.project.store') }}" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="form-group">
                                                            <input type="file" class="form-control" name="upload">
                                                        </div>
                                                        <input type="submit" class="btn btn-success" value="Submit">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- END FORM-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
