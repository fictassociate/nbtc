<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ระบบประเมินผล</title>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
    <link href="{{ asset('global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('global/plugins/bootstrap-switch/css/bootstrap-switch.min.css"') }} rel="stylesheet" type="text/css" />
    <!-- END GLOBAL MANDATORY STYLES -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ asset('global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL STYLES -->
    <link href="{{ asset('global/css/components.min.css') }}" rel="stylesheet" id="style_components" type="text/css" />
    <link href="{{ asset('global/css/plugins.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END THEME GLOBAL STYLES -->
    <!-- BEGIN PAGE LEVEL STYLES -->
    <link href="{{ asset('pages/css/login-2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL STYLES -->
</head>
<body class=" login">
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" alt="logo" class="logo-default" width="200px">
    </div>
    <div class="content">
        <form class="login-form" action="{{ route('login.login') }}" method="post">
            @csrf
            <div class="form-title">
                <span class="form-title">ยินดีต้อนรับ</span>
                <span class="form-subtitle">กรุณาเข้าสู่ระบบ</span>
            </div>
            @if ($msg = Session::get('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span>{{ $msg }}</span>
            </div>
            @endif
            @if (count($errors))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span>กรุณากรอกชื่อผู้ใช้และรหัสผ่าน</span>
            </div>
            @endif
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">ชื่อผู้ใช้</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="ชื่อผู้ใช้" name="USER_LOGIN">
            </div>
            <div class="form-group">
                <label class="control-label visible-ie8 visible-ie9">รหัสผ่าน</label>
                <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="รหัสผ่าน" name="PASSWORD">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn red btn-block uppercase">เข้าสู่ระบบ</button>
            </div>
        </form>
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
    <script src="{{ asset('global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('v') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="{{ asset('global/scripts/app.min.js') }}" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="{{ asset('pages/scripts/login.min.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
</body>
</html>
