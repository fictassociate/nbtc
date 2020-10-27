<li class="dropdown dropdown-user dropdown-dark">
    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
        <span class="username username-hide-on-mobile"> {{ Session::get('name') }} ({{ Session::get('department') }})</span>
    </a>
    <ul class="dropdown-menu dropdown-menu-default">
        <li>
            <a href="{{ route('login.logout') }}">
                <i class="icon-key"></i> Log Out </a>
        </li>
    </ul>
</li>
