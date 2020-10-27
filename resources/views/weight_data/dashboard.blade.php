@extends('layouts.master')
@section('title', 'ระบบประเมินผล')
@section('content-wrapper')
  <div class="page-content-wrapper">
    <div class="page-content">
      <ul class="page-breadcrumb breadcrumb">
        <li>
          <i class="icon-screen-desktop" style="font-size: 18px;"></i>
          <a href="{{ route('weight.subject') }}" class="breadcrumb-link">Dashboard - สรุปความก้าวหน้าประเด็นการพัฒนา [แผนงาน/โครงการ]</a>
        </li>
      </ul>
      <div class="row">
        <div class="col-md-12">
          <div class="portlet light bordered">
            <div class="row">
              <?php
                $i = 1;
              ?>
              @foreach ($subjects as $subject)
                <div class="col-md-3">
                  <a href="{{ route('weight.goal', ['subjectId'=>$subject['data']->ID_MAST_SUBJECT]) }}">
                    <div style="width: 100%; height: 100%;" class="text-center">
                      <canvas id="donut_{{$i}}" width="250" height="250"></canvas>
                    </div>
                    <span class="issue-header text-center">{{$subject['data']->SUBJECT_NAME}}</h4>
                  </a>
                </div>
                <?php
                  $i++
                ?>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
@push('script')
  <script src="{{ asset('js/donut-1.js') }}"></script>
  <script src="{{ asset('js/donut-2.js') }}"></script>
  <script>
    var i = 1;
    @foreach ($subjects as $subject)
      var perc = @php echo round(@$subject['progress']->TOTAL, 2); @endphp;
      Chart.types.Doughnut.extend({
        name: "DoughnutTextInside",
        showTooltip: function() {
            this.chart.ctx.save();
            Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
            this.chart.ctx.restore();
        },
        draw: function() {
            Chart.types.Doughnut.prototype.draw.apply(this, arguments);

            var width = this.chart.width,
                height = this.chart.height;

            var fontSize = (height / 114).toFixed(2);
            this.chart.ctx.font = fontSize + "em Verdana";
            this.chart.ctx.textBaseline = "middle";

            var text = @php echo round(@$subject['progress']->TOTAL, 2); @endphp + "%",
                textX = Math.round((width - this.chart.ctx.measureText(text).width) / 2),
                textY = height / 2;

            this.chart.ctx.fillText(text, textX, textY);
        }
      });

      switch(i) {
        case 1:
          var data = [{
            value: perc,
            color: "#89C4F4"
          }, {
              value: 100 - perc,
              color: "#BEBEBE"
          }];
          break;
        case 2:
          var data = [{
            value: perc,
            color: "#45B6AF"
          }, {
              value: 100 - perc,
              color: "#BEBEBE"
          }];
          break;
        case 3:
          var data = [{
            value: perc,
            color: "#d9534f"
          }, {
              value: 100 - perc,
              color: "#BEBEBE"
          }];
          break;
        case 4:
          var data = [{
            value: perc,
            color: "#dfba49"
          }, {
              value: 100 - perc,
              color: "#BEBEBE"
          }];
          break;
      }

      var DoughnutTextInsideChart = new Chart($('#donut_'+(i++))[0].getContext('2d')).DoughnutTextInside(data);
    @endforeach
  </script>
@endpush
