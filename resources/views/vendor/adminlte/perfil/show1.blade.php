@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-user"></i>  Perfil</h1>
@endsection

@section('main-content')
    <div class="row">

        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">             

                @if(empty($usuario->avatar))
                    <img class="profile-user-img img-responsive img-circle" src="{{ Gravatar::get($user->email) }}" alt="User profile picture">
                @else
                  <img class="profile-user-img img-responsive img-circle" src="{{Storage::url($usuario->avatar)}}" alt="User profile picture">
                @endif

              <h3 class="profile-username text-center">{{$usuario->name}}</h3>

              <p class="text-muted text-center">{{$usuario->roles[0]['display_name']}}</p>

                <?php 
                  $total = 0;
                  $aprobados = 0;
                  $pendientes = 0;
                  $rechazados = 0;

                  foreach ($clientes as $cliente) {
                    switch ($cliente->Status) {
                      case 'PENDIENTE':
                        $pendientes = $pendientes + $cliente->cantidad;        
                        break;
                      case 'RECHAZADO':
                        $rechazados = $rechazados + $cliente->cantidad;
                        break;
                      case 'ANULADO':
                        $rechazados = $rechazados + $cliente->cantidad;
                        break;
                      default:
                        $aprobados = $aprobados + $cliente->cantidad;
                        break;
                    }

                    $total += $cliente->cantidad;
                  }

                ?>
              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Total Clientes</b> <a class="pull-right">{{number_format($total, 0, ',','.')}}</a>
                </li>
                <li class="list-group-item">
                  <b>Aprobados</b> <a class="pull-right">{{number_format($aprobados, 0, ',','.')}}</a>
                </li>
                <li class="list-group-item">
                  <b>Pendientes</b> <a class="pull-right">{{number_format($pendientes, 0, ',','.')}}</a>
                </li>
                <li class="list-group-item">
                  <b>Rechazados</b> <a class="pull-right">{{number_format($rechazados, 0, ',','.')}}</a>
                </li>
              </ul>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#estadisticas" data-toggle="tab" aria-expanded="true">Estadisticas</a></li>
                @if(Auth::user()->id == $usuario->id)
                  <li class=""><a href="#configuracion" data-toggle="tab" aria-expanded="false">Configuracion</a></li>
                @endif
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="estadisticas">
                    <div class="row">
                        <div class="col-md-12">
                            {!! $grafica_fecha_clientes->html() !!}
                            <hr>
                        </div>                        
                    </div>
              </div>
              <!-- /.tab-pane -->
            @if(Auth::user()->id == $usuario->id)
              <div class="tab-pane" id="configuracion">
                <form class="form-horizontal" action="{{route('perfil.update', Auth::user()->id)}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PUT">

                    <div class="form-group{{ $errors->has('cedula') ? ' has-error' : '' }}">
                        <label class="col-sm-2 control-label">Cedula</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="cedula" value="{{$usuario->cedula}}" disabled>

                            @if ($errors->has('cedula'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('cedula') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('celular') ? ' has-error' : '' }}">
                        <label class="col-sm-2 control-label">Celular</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="celular" value="{{$usuario->celular}}" disabled>

                            @if ($errors->has('celular'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('celular') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-sm-2 control-label">Correo</label>
                        <div class="col-sm-10">
                            <input id="email" type="email" class="form-control" name="email" value="{{$usuario->email}}" disabled>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                        <label for="password" class="col-sm-2 control-label">Contraseña</label>

                        <div class="col-sm-4">
                            <input id="password" type="password" class="form-control" name="password">
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <label for="password-confirm" class="col-sm-2 control-label">Confirmar</label>
                        <div class="col-sm-4">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputExperience" class="col-sm-2 control-label">Avatar</label>
                        <div class="col-sm-10">
                            <input type="file" name="avatar" class="form-control">
                            <span>Cambiar Foto (jpeg, png, 160x160 pixels, 96dpi, tamaño menor 25KB):</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="inputExperience" class="col-sm-2 control-label">Firma</label>
                        <div class="col-sm-10">
                            <input type="file" name="firma" class="form-control">
                            <span>Descargue la aplicacion para firmar <a href="https://play.google.com/store/apps/details?id=com.signaturemaker.app" target="_black">aquí</a></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                    </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            @endif
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
    </div>
    @section('mis_scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.10.1/chartist.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
        <script type="text/javascript" src="https://static.fusioncharts.com/code/latest/fusioncharts.js"></script>
        <script type="text/javascript" src="https://static.fusioncharts.com/code/latest/themes/fusioncharts.theme.fint.js"></script>

        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/highcharts.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/js/modules/offline-exporting.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.6/raphael.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/justgage/1.2.2/justgage.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.6/raphael.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/plottable.js/2.8.0/plottable.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/echarts/3.6.2/echarts.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.2/amcharts.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.2/serial.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.2/plugins/export/export.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.2/themes/light.js"></script>
      {!! $grafica_fecha_clientes->script() !!}
    @endsection
@endsection