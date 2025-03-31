@extends('adminlte::layouts.auth')

@section('htmlheader_title')
    Password recovery
@endsection

@section('content')

<body class="login-page" style="background-image: url('../img/fondo.jpg') !important; background-repeat: no-repeat; background-size: cover; background-position: center top; width: 100%; height: 100%; opacity: 1; visibility: inherit; z-index: 20; overflow:hidden;">
    <div id="app">

        <div class="login-box" >
        <div class="login-logo">
            <a href="{{ url('/home') }}"> <img src="/img/logo.png" width="80px">Soft</a> </a>
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
            <p class="login-box-msg">Reset Password</p>

            <email-reset-password-form></email-reset-password-form>

            <a href="{{ url('/login') }}">Iniciar Sesión</a><br>            

        </div><!-- /.login-box-body -->

    </div><!-- /.login-box -->
    </div>

        <footer style="position: absolute; bottom: 0px; width: 100%;">
        <div style="background: rgb(0,74,132); color: #fff; padding-top: 15px; text-align: center;">
            <div class="container">
                <p>
                    <strong>Copyright &copy; 2019</a>.  
                </p>

            </div>
        </div>
    </footer>

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
</body>

@endsection
