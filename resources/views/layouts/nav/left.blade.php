<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu   " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
            <li class="nav-item start {{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ route('index') }}" class="nav-link nav-toggle">
                    <i class="icon-bar-chart"></i>
                    <span class="title">ภาพรวม</span>
                </a>
            </li>
            <li class="nav-item open {{ request()->is('project*') ? 'active' : '' }}">
                <a href="javascript:;" class="nav-link nav-toggle" style=" {{ request()->is('project*') ? '' : 'background: none;' }}">
                    <span class="title">โครงการ</span>
                    <span class="arrow open"></span>
                </a>
                <ul class="sub-menu" style="display: block;">
                    <li class="nav-item {{ request()->is('project') ? 'active' : '' }}">
                        <a href="{{ route('project.index') }}" class="nav-link nav-toggle">
                            <i class="icon-social-dropbox"></i>
                            <span class="title">โครงการทั้งหมด</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('project/create') ? 'active' : '' }}">
                        <a href="{{ route('project.create') }}" class="nav-link nav-toggle">
                            <i class="icon-plus"></i>
                            <span class="title">เพิ่มโครงการใหม่</span>
                        </a>
                    </li>
                </ul>
            </li>
            @if (Session::get('role') == 'S')
                <li class="nav-item open {{ request()->is('develop_plan*') || request()->is('subject*') || request()->is('goal*') || request()->is('department*') || request()->is('user*') ? 'active' : '' }}">
                    <a href="javascript:;" class="nav-link nav-toggle" style="{{ request()->is('user*') || request()->is('department*') || request()->is('develop_plan*') || request()->is('subject*') || request()->is('goal*') ? '' : 'background: none' }}">
                        <span class="title">ข้อมูลหลัก</span>
                        <span class="arrow open"></span>
                    </a>
                    <ul class="sub-menu" style="display: block;">
                        <li class="nav-item {{ request()->is('develop_plan*') ? 'active' : '' }}">
                            <a href="{{ route('develop_plan.index') }}" class="nav-link nav-toggle">
                                <i class="icon-globe"></i>
                                <span class="title">แผนยุทธศาสตร์</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('subject*') ? 'active' : '' }}">
                            <a href="{{ route('subject.index') }}" class="nav-link nav-toggle">
                                <i class="icon-eyeglasses"></i>
                                <span class="title">ยุทธศาสตร์</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('goal*') ? 'active' : '' }}">
                            <a href="{{ route('goal.index') }}" class="nav-link nav-toggle">
                                <i class="icon-drop"></i>
                                <span class="title">กลยุทธ์</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('indicator*') ? 'active' : '' }}">
                            <a href="{{ route('indicator.index') }}" class="nav-link nav-toggle">
                                <i class="icon-compass"></i>
                                <span class="title">ตัวชี้วัด</span>
                            </a>
                        </li>
                        <li class="nav-item {{ request()->is('user*') ? 'active' : '' }}"">
                            <a href="{{ route('user.index') }}" class="nav-link nav-toggle">
                                <i class="icon-user"></i>
                                <span class="title">ผู้ใช้งานในระบบ</span>
                            </a>
                        </li>
                        {{-- <li class="nav-item {{ request()->is('department*') ? 'active' : '' }}">
                            <a href="{{ route('department.index') }}" class="nav-link nav-toggle">
                                <i class="icon-tag"></i>
                                <span class="title">หน่วยงาน</span>
                            </a>
                        </li> --}}
                    </ul>
                </li>
            @endif
            <li class="nav-item">
                <a href="{{ asset('images/01340_คู่มือระบบการใช้งาน_630806_2.pdf') }}" class="nav-link nav-toggle">
                    <i class="icon-support"></i>
                    <span class="title">คู่มือการใช้งาน</span>
                </a>
            </li>
        </ul>
    </div>
</div>
