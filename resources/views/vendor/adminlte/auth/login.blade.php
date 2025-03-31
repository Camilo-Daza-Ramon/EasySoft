@extends('adminlte::layouts.auth')

@section('htmlheader_title')
    Log in
@endsection

@section('content')
<body class="hold-transition login-page" style="background-image: url('img/fondo.jpg') !important; background-repeat: no-repeat; background-size: auto; background-position: center top; width: 100%; height: 100%; opacity: 1; visibility: inherit; z-index: 20; overflow:hidden;">
    
    <div id="app" v-cloak>
        <div class="login-box">
            <div class="login-logo" style="padding-top: 10px;">
                <a href="{{ url('/home') }}"> <img src="/img/logo.png" width="80px">Soft</a> </a>
            </div><!-- /.login-logo -->

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
        <p class="login-box-msg"> {{ trans('adminlte_lang::message.siginsession') }} </p>

        <login-form name="{{ config('auth.providers.users.field','email') }}"
                    domain="{{ config('auth.defaults.domain','') }}"></login-form>

    

        <a href="{{ url('/password/reset') }}">{{ trans('adminlte_lang::message.forgotpassword') }}</a><br>

    </div>

    </div>
    </div>
    @include('adminlte::layouts.partials.scripts_auth')

    <script>

        $(".row").children().removeClass("col-xs-8").addClass("col-xs-6");

        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
    <footer style="position: absolute; bottom: 0px; width: 100%;">
        <div style="background: rgb(0,74,132); color: #fff; padding-top: 15px; text-align: center;">
            <div class="container">
                <p>
                    <strong>Copyright &copy; {{date('Y')}}</strong>.  
                </p>

            </div>
        </div>
    </footer>
</body>

@endsection
