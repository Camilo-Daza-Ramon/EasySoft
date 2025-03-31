<!DOCTYPE html>
<!--
Landing page based on Pratt: http://blacktie.co/demo/pratt/
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="K&J SOLUCIONES {{ trans('adminlte_lang::message.landingdescription') }} ">
    <meta name="author" content="Jonathan Cardona">
    <link rel="icon" type="image/png" href="img/favicon.ico"/>

    <meta property="og:title" content="SISTECO S.A.S" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="SISTECO S.A.S - {{ trans('adminlte_lang::message.landingdescription') }}" />
    <meta property="og:url" content="http://demo.adminlte.acacha.org/" />
    <meta property="og:image" content="http://demo.adminlte.acacha.org/img/AcachaAdminLTE.png" />
    <meta property="og:image" content="http://demo.adminlte.acacha.org/img/AcachaAdminLTE600x600.png" />
    <meta property="og:image" content="http://demo.adminlte.acacha.org/img/AcachaAdminLTE600x314.png" />
    <meta property="og:sitename" content="demo.adminlte.acacha.org" />
    <meta property="og:url" content="http://demo.adminlte.acacha.org" />

    <title>Easy-Soft</title>

    <!-- Custom styles for this template -->
    <link href="{{ asset('/css/all-landing.css') }}" rel="stylesheet">
    <link href="css/all.css" rel="stylesheet" type="text/css" />  
    <link href="css/login.css" rel="stylesheet" type="text/css" />  
  

    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,300italic,400italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Raleway:400,300,700' rel='stylesheet' type='text/css'>

</head>

<body class="hold-transition login-page" style="background-image: url('img/fondo.jpg') !important; background-repeat: no-repeat; background-size: cover; background-position: center top; width: 100%; height: 100%; opacity: 1; visibility: inherit; z-index: 20; overflow:hidden;">

<div id="app" v-cloak>



    <section id="home" name="home" >
        
        <div class="container">
            <div class="row centered">
                <div class="col-lg-12">
                    <h1 style="color: #777;"><img src="img/logo.png" width="120px">Soft  </h1>                      
                    <h3>                            
                        @if (Auth::guest())
                            <a href="{{ url('/login') }}" class="btn btn-lg btn-info">{{ trans('adminlte_lang::message.login') }}</a>
                        @else
                            <a href="/home" class="btn btn-lg btn-success">{{ Auth::user()->name }}</a>
                        @endif
                    </h3>
                </div>
                <div class="col-lg-2">
                    <div style="height: 210px"></div>
                </div>
                <div class="col-lg-8">
                    
                </div>
                <div class="col-lg-2">
                    
                </div>
            </div>
        </div> <!--/ .container -->
        
    </section>
    <footer style="position: absolute; bottom: 0px; width: 100%;">
        <div style="background: rgb(0,74,132); color: #fff; padding-top: 15px; text-align: center;">
            <div class="container">
                <p>
                    <strong>Copyright &copy;{{date('Y')}}</a>.  
                </p>

            </div>
        </div>
    </footer>

</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="{{ url (mix('/js/app-landing.js')) }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
<script type="text/javascript" src="{{asset('js/login.js')}}"></script>
<script>
    $('.carousel').carousel({
        interval: 3500
    })
</script>
</body>
</html>
