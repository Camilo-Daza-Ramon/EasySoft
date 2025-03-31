@extends('adminlte::layouts.auth')

@section('htmlheader_title')
    Password reset
@endsection

@section('content')

    <body class="login-page" style="background-image: url('/img/fondo.jpg') !important; background-repeat: no-repeat; background-size: cover; background-position: center top; width: 100%; height: 100%; opacity: 1; visibility: inherit; z-index: 20; overflow:hidden;">

    <div id="app">
        <div class="login-box">
        <div class="login-logo">
            <a href="{{ url('/home') }}" style="color: #fff;"> <img src="/img/logo.png" width="80px">Soft</a> </a>
        </div><!-- /.login-logo -->

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>Whoops!</strong> {{ trans('adminlte_lang::message.someproblems') }}<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="login-box-body">
            <p class="login-box-msg">{{ trans('adminlte_lang::message.passwordreset') }}</p>

            <reset-password-form token="{{ $token }}">></reset-password-form>

            <a href="{{ url('/login') }}">Log in</a><br>
            <a href="{{ url('/register') }}" class="text-center">{{ trans('adminlte_lang::message.membreship') }}</a>

        </div><!-- /.login-box-body -->

    </div><!-- /.login-box -->
    </div>

    @include('adminlte::layouts.partials.scripts_auth')

    <script>
        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
    <footer style="position: absolute; bottom: 0px; width: 100%;">
        <div style="background: rgb(0,74,132); padding-top: 15px; text-align: center;">
            <div class="container">
                <p>
                    <strong>Copyright &copy; 2019 <a href="http://www.sisteco.com.co">Sisteco S.A.S</a>.</strong> {{ trans('adminlte_lang::message.createdby') }} <a href="http://acacha.org/sergitur">Sisteco S.A.S</a>.  
                </p>

            </div>
        </div>
    </footer>
    </body>

@endsection
