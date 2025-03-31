@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-user"></i>  Perfil</h1>
@endsection

@section('main-content')
    <div class="row">

        <div class="col-md-4">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">             

                @if(empty($usuario->avatar))
                    <img class="profile-user-img img-responsive img-circle" src="{{ Gravatar::get($user->email) }}" alt="User profile picture">
                @else
                  <img class="profile-user-img img-responsive img-circle" src="{{Storage::url($usuario->avatar)}}" alt="User profile picture">
                @endif

              <p class="text-muted text-center">{{$usuario->name}}</p>

              <p class="text-muted text-center">{{$usuario->roles[0]['display_name']}}</p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Nombre</b> <a class="pull-right">{{$usuario->name}}</a>
                </li>
                <li class="list-group-item">
                  <b>Cedula</b> <a class="pull-right">{{$usuario->cedula}}</a>
                </li>
                <li class="list-group-item">
                  <b>Telefono</b> <a class="pull-right">{{$usuario->celular}}</a>
                </li>
                <li class="list-group-item">
                  <b>Correo</b> <a class="pull-right">{{$usuario->email}}</a>
                </li>

              @if(!empty($usuario->firma))
                <a href="{{Storage::url($usuario->firma)}}" class="btn btn-sm btn-primary btn-block" download> Firma</a>
              @endif
            

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>        
    </div>
    
@endsection