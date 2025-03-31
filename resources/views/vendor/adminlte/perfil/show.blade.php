@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-user"></i>  Perfil</h1>
@endsection

@section('main-content')
    <div class="row">
    	<div class="col-md-7">
    		<div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-blue">
              <h3 class="widget-user-username">{{$usuario->name}}</h3>
              <h5 class="widget-user-desc">{{$usuario->roles[0]['display_name']}}</h5>
            </div>
            <div class="widget-user-image">
              @if(empty($usuario->avatar))
                  <img class="img-circle" src="{{ Gravatar::get($user->email) }}" alt="User profile picture">
                @else
                  <img class="img-circle" src="{{Storage::url($usuario->avatar)}}" alt="User profile picture">
                @endif
            </div>
            <div class="box-footer">
              <div class="row">
                <div class="col-sm-12">
                    <br>
                	<form class="form-horizontal" action="{{route('perfil.update', Auth::user()->id)}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
			            <input name="_method" type="hidden" value="PUT">                                
			            {{ csrf_field() }}
			            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        <label for="name" class="col-sm-2 control-label">Nombre</label>

                        <div class="col-sm-10">
                            <input id="name" type="text" class="form-control" name="name" value="{{$usuario->name}}" disabled>

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                             @endif
                        </div>
                    </div>

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
                            <input type="number" class="form-control" name="celular" value="{{$usuario->celular}}">

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
                    
                  		<button type="submit" class="btn btn-info pull-right">Actualizar</button>
                  	</form>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->                
              </div>
              <!-- /.row -->
            </div>
          </div>
    		
    	</div>
</div>
@endsection