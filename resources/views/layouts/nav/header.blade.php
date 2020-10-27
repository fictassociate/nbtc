<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner">
        <div class="page-logo">
            <a href="{{ route('index') }}">
                <img src="{{ asset('images/logo.png') }}" alt="logo" class="logo-default" width="75px">
            </a>
            <div class="menu-toggler sidebar-toggler"></div>
        </div>
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"></a>
        <div class="page-actions">
            <div class="btn-group">
                <button type="button" class="btn red-haze btn-sm dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                    <span class="hidden-sm hidden-xs">{{ substr(Session::get('plan_name'), 0, 200) }}&nbsp;</span>
                    <i class="fa fa-angle-down"></i>
                </button>
                <ul class="dropdown-menu" role="menu">
                    @foreach (Session::get('plan') as $p)
                        <li> <a href="{{ route('session.plan', ['plan_id'=>$p->ID_MAST_DEV]) }}">{{ $p->PLAN_NAME }}</a> </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="page-top">
            {{-- @include('layouts.nav.sub.search') --}}
            <div class="top-menu">
                <ul class="nav navbar-nav pull-right">
                    <li class="separator hide"></li>
                    {{-- @include('layouts.nav.sub.notification') --}}
                    {{-- <li class="separator hide"></li> --}}
                    {{-- @include('layouts.nav.sub.inbox') --}}
                    {{-- <li class="separator hide"></li> --}}
                    {{-- @include('layouts.nav.sub.task') --}}
                    @include('layouts.nav.sub.user')
                </ul>
            </div>
        </div>
    </div>
</div>
