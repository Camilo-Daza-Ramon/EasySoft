<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="{{ url('home') }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"> <img src="{{asset('img/logo.png')}}" width="40px"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="{{asset('img/logo.png')}}" width="40px">Soft</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">{{ trans('adminlte_lang::message.togglenav') }}</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav" id="notificacion_up">               


               
                @if (Auth::guest())
                    <li><a href="{{ url('/register') }}">{{ trans('adminlte_lang::message.register') }}</a></li>
                    <li><a href="{{ url('/login') }}">{{ trans('adminlte_lang::message.login') }}</a></li>
                @else
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu" id="user_menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            @if(!empty(Auth::user()->avatar))                            
                                <img src="{{Storage::url(Auth::user()->avatar)}}" class="user-image" alt="User Image"/>
                            @else
                            <img src="{{Gravatar::get(Auth::user()->email) }}" class="user-image" alt="User Image"/>
                            @endif
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                @if(!empty(Auth::user()->avatar)) 
                                    <img src="{{ Storage::url(Auth::user()->avatar)}}" class="img-circle" alt="User Image" />
                                @else
                                    <img src="{{Gravatar::get(Auth::user()->email) }}" class="img-circle" alt="User Image" />
                                @endif
                                <p>
                                    {{ Auth::user()->name }}
                                    <small>{{Auth::user()->roles->get(0)->display_name}}</small>
                                </p>
                            </li>                    
                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="{{ url('perfil/' . Auth::user()->id) }}" class="btn btn-default btn-flat">{{ trans('adminlte_lang::message.profile') }}</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ url('/logout') }}" class="btn btn-default btn-flat" id="logout"
                                       onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        {{ trans('adminlte_lang::message.signout') }}
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                        <input type="submit" value="logout" style="display: none;">
                                    </form>

                                </div>
                            </li>
                        </ul>
                    </li>
                @endif     
            </ul>
        </div>
    </nav>
</header>
