@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
    <div class="page-content-wrapper">
        <div class="page-content">
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead style="background: #003366;color: white">
                                        <tr>
                                            <th width="50%">ผลลัพธ์</th>
                                            <th width="25%">โครงการ</th>
                                            <th width="25%">หน่วยงาน</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $project_code = "";
                                            $i = 1;
                                        @endphp
                                        @foreach ($data['mast'] as $mast)
                                            @if ($mast['project_code'] != $project_code)
                                                <tr>
                                                    <td>
                                                        <div id="barchart_material_{{ $i }}" style="width: 100%"></div>
                                                    </td>
                                                    <td>
                                                        {{ $mast['project_name'] }} <br>
                                                    </td>
                                                    <td>
                                                        {{ $mast['department_name'] }} <br>
                                                    </td>
                                                </tr>
                                            @endif
                                            @php
                                                $i++;
                                                $project_code = $mast['project_code'];
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
    </div>
@endsection
@push('script')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['bar']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            @php
                $project_code = "";
                $i = 1;
            @endphp
            @foreach ($data['mast'] as $mast)
                var data = google.visualization.arrayToDataTable([
                ['ไตรมาส', 'ผลลัพธ์ขั้นต้น', 'ผลลัพธ์ขั้นกลาง', 'ผลลัพธ์ขั้นปลาย'],
                @foreach ($mast['quarter'] as $quarter)
                ['ไตรมาส ' + {{ $quarter['quarter'] }}, {{ $quarter['begin'] }}, {{ $quarter['mid'] }}, {{ $quarter['end'] }}],
                @endforeach
                // ['1', 1000, 400, 200],
                // ['2', 1170, 460, 250],
                // ['3', 660, 1120, 300],
                // ['4', 1030, 540, 350],
                ]);

                var options = {
                    bars: {groupWidth: "70%"} // Required for Material Bar Charts.
                };
                @if ($mast['project_code'] != $project_code)
                    var chart = new google.charts.Bar(document.getElementById('barchart_material_{{ $i }}'));

                    chart.draw(data, google.charts.Bar.convertOptions(options));
                @endif
                @php
                    $project_code = $mast['project_code'];
                    $i++;
                @endphp
            @endforeach
        }
    </script>
@endpush
