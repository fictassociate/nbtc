<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
    <link href="{{ asset('global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css">
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ asset('global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('global/plugins/bootstrap-daterangepicker/daterangepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('global/plugins/clockface/css/clockface.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{ asset('global/css/components.min.css') }}" rel="stylesheet" id="style_components" type="text/css">
    <link href="{{ asset('global/css/plugins.min.css') }}" rel="stylesheet" type="text/css">
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN THEME LAYOUT STYLES -->
    <link href="{{ asset('layouts/layout/css/layout.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('layouts/layout/css/themes/default.min.css') }}" rel="stylesheet" type="text/css" id="style_color">
    <link href="{{ asset('layouts/layout/css/custom.min.css') }}" rel="stylesheet" type="text/css">
    <!-- END THEME LAYOUT STYLES -->
    {{-- <link rel="shortcut icon" href="favicon.ico"> --}}
    @stack('style');
    <style>
      .issue-header{
        font-size: 16px;
        color : #678098;
        display: inline-block;
        text-align: center;
      }

      .breadcrumb{
        color : #000;
      }

      a.breadcrumb-link{
        font-size: 15px;
      }

      .breadcrumb-current{
        font-size: 15px;
        color: #678098;
      }
    </style>
</head>
<body class="page-container-bg-solid page-header-fixed page-sidebar-closed-hide-logo">
    @include('layouts.nav.header')
    <div class="clearfix"></div>
    <div class="page-container">
        @include('layouts.nav.left')
        <a href="javascript:;" class="page-quick-sidebar-toggler">
            <i class="icon-login"></i>
        </a>
        @yield('content-wrapper')
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
    </div>




    <!-- BEGIN CORE PLUGINS -->
    <script src="{{ asset('global/plugins/jquery.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/js.cookie.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ asset('global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/bootstrap-select/js/bootstrap-select.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/bootstrap-daterangepicker/daterangepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/clockface/js/clockface.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="{{ asset('global/scripts/app.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/scripts/datatable.js') }}" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{ asset('pages/scripts/table-datatables-rowreorder.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('pages/scripts/components-select2.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('pages/scripts/components-date-time-pickers.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('pages/scripts/components-bootstrap-switch.min.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <script src="{{ asset('layouts/layout/scripts/layout.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('layouts/layout/scripts/demo.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('layouts/global/scripts/quick-sidebar.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('layouts/global/scripts/quick-nav.min.js') }}" type="text/javascript"></script>
    <!-- END THEME LAYOUT SCRIPTS -->
    <script>
        $('.date-picker').datepicker({language:'th-th',format:'dd/mm/yyyy'})
    </script>
    @stack('script')
</body>
</html>
