@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
    <div class="page-content-wrapper">
        <div class="page-content">
          <ul class="page-breadcrumb breadcrumb">
            <li>
              <i class="icon-screen-desktop" style="font-size: 18px;"></i>
              <a href="{{ route('index') }}" class="breadcrumb-link">Dashboard</a>
            </li>
          </ul>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        @if (count($data['subject']))
                        <div class="row">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($data['subject'] as $subject)
                                <div class="col-md-3">
                                    <a href="#subject_{{ $subject->ID_MAST_SUBJECT }}">
                                        <div style="width: 100%; height: 100%;" class="text-center">
                                            <canvas id="donut-{{ $i }}" width="1" height="1"></canvas>
                                        </div>
                                        <h4 class="text-center" style="line-height: 1.6;color: rgb(0, 17, 168);">{{ $subject->SUBJECT_NAME }}</h4>
                                    </a>
                                </div>
                                @php
                                    $i++;
                                @endphp
                            @endforeach
                        </div>
                        @else
                        <div class="row">
                            <div class="col-md-12" style="width: 100%;height: 500px;display: grid;align-items: center;text-align: center;">
                                <h1 style="color: red;font-weight: 900;font-size: 60px">ยังไม่มีข้อมูลแผนยุทธศาสตร์</h1>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @if (count($data['subject']))
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="row">
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($data['mast'] as $mast)
                                <div class="col-md-12" id="subject_{{ $mast['ID_MAST_SUBJECT'] }}">
                                    <h3><label class="label label-xs label-success ">ยุทธศาสตร์</label> {{ $mast['SUBJECT_NAME'] }}</h3>
                                    @foreach ($mast['GOAL_GROUP'] as $goal)
                                        <div class="col-md-12">
                                            <h4 class="goal-line-height"><label class="label label-xs label-info ">กลยุทธ์</label> {{ $goal['GOAL_NAME'] }}</h4>
                                            @foreach ($goal['INDICATOR'] as $indicator)
                                                <div class="col-md-4 indicator-box">
                                                    <div id="barchart_material_{{ $i }}" style="width: 100%"></div>
                                                    <h5 class="indicator-height text-center"><label class="label label-xs bg-yellow-casablanca ">ตัวชี้วัด</label><a class="view_modal" id="{{$indicator['ID_MAST_INDICATOR']}}" rel-section="{{ $indicator['INDICATOR_NAME'] }}"> {{ $indicator['INDICATOR_NAME'] }}</a></h5>
                                                </div>
                                                @php
                                                    $i++;
                                                @endphp
                                            @endforeach
                                        </div>
                                    @endforeach
                                    <hr style="border: 1px solid black">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="dataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            <h4 id="modal-title" style="line-height: 1.6"></h4>
        </div>
        <div class="modal-body" id="project_list">
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
</div>
@endsection
@push('style')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
    <style>
        .indicator-height {
            height: 90px;
            line-height: 1.6;
        }

        .goal-line-height {
            line-height: 1.6;
        }

        .indicator-box {
            padding: 10px 10px;
        }
    </style>
@endpush
@push('script')
    <script>
        $(document).ready(function() {
            $('.view_modal').on('click', function() {
                var indicator_id = $(this).attr('id');
                var ind_name = $(this).attr('rel-section')
                $.ajax({
                    url: "{{ route('show_project') }}",
                    method: 'GET',
                    data: {indicator_id:indicator_id},
                    success: function(result) {
                        $('#dataModal').modal('show');
                        $('#modal-title').text(ind_name);
                        $('#project_list').html(result);
                    }
                })
            })
        })
    </script>
    <script>
        @php
            $i = 1;
        @endphp
        @foreach ($data['subject'] as $subject)
            Chart.types.Doughnut.extend({
                name: "DoughnutTextInside",
                showTooltip: function() {
                    this.chart.ctx.save();
                    // Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
                    this.chart.ctx.restore();
                },
                draw: function() {
                    Chart.types.Doughnut.prototype.draw.apply(this, arguments);

                    var width = this.chart.width,
                        height = this.chart.height;

                    var fontSize = (height / 114).toFixed(2);
                    this.chart.ctx.font = fontSize + "em Verdana";
                    this.chart.ctx.textBaseline = "middle";
                    var text = "0%",
                    @foreach ($data['subject_score'] as $score)
                        @if ($score->id_mast_subject == $subject->ID_MAST_SUBJECT)
                            text = "{{ round($score->perc) }}%",
                        @endif
                    @endforeach
                        textX = Math.round((width - this.chart.ctx.measureText(text).width) / 2),
                        textY = height / 2;

                    this.chart.ctx.fillText(text, textX, textY);
                }
            });
            var score = 0;
            var btn_color = "#F7464A";
            switch ({{ $i }}) {
                case 1 : btn_color = "#7FFF00";break;
                case 2 : btn_color = "#FFD700";break;
                case 3 : btn_color = "#FA8072";break;
                case 4 : btn_color = "#0000CD";break;
            }
            @foreach ($data['subject_score'] as $score)
                @if ($score->id_mast_subject == $subject->ID_MAST_SUBJECT)
                    score = {{ round($score->perc) }};
                @endif
            @endforeach
            var data = [{
                value: score,
                color: btn_color
            }, {
                value: 100 - score,
                color: "gray"
            }];

            var DoughnutTextInsideChart = new Chart($('#donut-' + {{ $i }})[0].getContext('2d')).DoughnutTextInside(data, {
                responsive: true
            });
            @php
                $i++;
            @endphp
        @endforeach
    </script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawVisualization);

        function drawVisualization() {
            @php
                $i = 1;
            @endphp
            @foreach ($data['mast'] as $mast)
                @foreach ($mast['GOAL_GROUP'] as $goal)
                    @foreach ($goal['INDICATOR'] as $indicator)
                        var chart0 = parseInt("{{ @$indicator['PERCENT'][0]['PERCENT_SUCCESS'] }}");
                        var chart1 = parseInt("{{ @$indicator['PERCENT'][1]['PERCENT_SUCCESS'] }}");
                        var chart2 = parseInt("{{ @$indicator['PERCENT'][2]['PERCENT_SUCCESS'] }}");
                        var chart3 = parseInt("{{ @$indicator['PERCENT'][3]['PERCENT_SUCCESS'] }}");
                        var chart4 = parseInt("{{ @$indicator['PERCENT'][4]['PERCENT_SUCCESS'] }}");
                        // Some raw data (not necessarily accurate)
                        var data = google.visualization.arrayToDataTable([
                        ['ปี', 'เปอร์เซนต์', 'เปอร์เซนต์'],
                        ['2561',  chart0,      chart0],
                        ['2562',  chart1,      chart1],
                        ['2563',  chart2,      chart2],
                        ['2564',  chart3,      chart3]
                        ]);

                        var options = {
                            vAxis: {title: 'เปอร์เซนต์'},
                            width:400,
                            seriesType: 'bars',
                            series: {1: {type: 'line',color:'#1E90FF'}},
                            legend: { position: "none" }
                        };

                        var chart = new google.visualization.ComboChart(document.getElementById('barchart_material_' + {{ $i }}));
                        chart.draw(data, options);
                        @php
                            $i++;
                        @endphp
                    @endforeach
                @endforeach
            @endforeach
        }
    </script>
@endpush
