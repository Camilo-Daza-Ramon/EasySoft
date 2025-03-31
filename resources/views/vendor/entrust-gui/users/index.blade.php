@extends('adminlte::layouts.app')

@section('contentheader_title')
  <h1> <i class="fa fa-users">  </i>  Usuarios</h1>
@endsection

@section('main-content')
  <div class="container-fluid spark-screen">
    <div class="row">
      <div class="col-md-12">
        <!-- Default box -->
        <div class="box box-info">
          <div class="box-header with-border bg-blue">
            <form class="navbar-form navbar-left" action="{{route('usuarios')}}" role="search" method="GET">
              <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Nombre de usuario">
              </div>
              <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
            <div class="box-tools">
              <a href="{{route('entrust-gui::users.create')}}" class="btn btn-default btn-sm">
                <i class="fa fa-plus"></i> Agregar</a> 
            </div>
          </div>

          <div class="box-body table-responsive">
            <table class="table table-striped"> 
              <tr>                
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
              @foreach($users as $user)
                <tr>
                  <td>{{$user->name}}</td>
                  <td>{{$user->email}}</td>
                  <td>{{$user->roles->get(0)->display_name}}</td>
                  <td>
                    <span class="label label-primary">{{$user->estado}}</span>
                  </td>
                  <td>
                    @role('admin')
                      <form action="{{ route('entrust-gui::users.destroy', $user->id) }}" method="post">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <a href="{{ route('entrust-gui::users.edit', $user->id) }}" class="btn btn-success btn-xs"><span class="fa fa-eye" aria-hidden="true"></span>
                        </a>

                        <a href="" class="btn btn-primary btn-xs" title="Editar">
                            <i class="fa fa-edit"></i>
                        </a>

                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                          <i class="fa fa-trash-o"></i>   
                        </button>                                               
                      </form>
                    @endrole
                  </td>
                </tr>
              @endforeach
              <tfoot>
                <tr>
                  <td colspan="7">
                    <center>{!! $users->render() !!}</center>
                  </td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection