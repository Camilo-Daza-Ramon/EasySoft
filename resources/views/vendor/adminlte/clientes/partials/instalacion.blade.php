<div class="col-md-12">
  <div class="panel panel-default"> 
    <div class="panel-heading">
      <h2><i class="fa fa-hdd-o"></i>  Instalaciones</h2> 
    </div>
    <div class="panel-body table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th style="width: 10px">#</th>
            <th>Serial</th>
            <th>Fecha</th>
            <th>Tecnico</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
            @if($cliente->instalaciones()->count() > 0)
                @foreach($cliente->instalaciones as $instalacion)
                <tr>
                    <td>
                        <a href="{{route('instalaciones.show', $instalacion->id)}}">{{$instalacion->id}}</a>
                    </td>
                    <td>{{$instalacion->serial_ont}}</td>
                    <td>{{$instalacion->fecha}}</td>
                    <td>{{$instalacion->tecnico->name}}</td>
                    <td>{{$instalacion->estado}}</td>
                    <td>
                        <a href="{{route('instalacion.pdf', $instalacion->id)}}" class="btn btn-default btn-xs" title="Formato de Instalacion" target="_blank"> <i class="fa fa-file-pdf-o"></i></a>

                            @permission('instalacion-edit')
                                <a href="{{route('instalaciones.edit', $instalacion->id)}}" class="btn btn-primary btn-xs"  target="_blank"> <i class="fa fa-edit"></i></a>
                            @endpermission    

                            @if($instalacion->estado == 'PENDIENTE')

                                @permission('instalacion-eliminar')
                                    <form action="{{route('instalaciones.destroy', $instalacion->id)}}" method="post" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="delete"> 
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <button type="submit" onclick="return confirm('Estas seguro Eliminar la instalacion?');" title="Eliminar" class="btn btn-danger btn-xs">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </form>
                                @endpermission
                            @endif
                    </td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center text-gray" colspan="6">NO HAY REGISTROS</td>
                </tr>
            @endif
        </tbody>
      </table>
    </div>
  </div>
</div>