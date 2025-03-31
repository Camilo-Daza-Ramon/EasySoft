<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-map-marker"></i>  Direcciones
    @if($mantenimiento->estado != 'CERRADO')
      <div class="btn-group pull-right">
        <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
          <span id="icon-opciones" class="fa fa-gears"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
          @permission('mantenimientos-direcciones-crear')
          <li>
            <a data-id_mantenimiento="{{$mantenimiento_id}}" id="btn-add-direccion" href="#" data-toggle="modal" data-target="#addDireccion">
              <i class="fa fa-plus"></i>  Agregar
            </a>
          </li>
          @endpermission            
        </ul>
      </div>
    @endif
    </h2>
  </div> 
  <div class="panel-body table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Direccion</th>
          <th>Barrio</th>
          <th>Coordenadas</th>
          <th>Acciones</th>
        </tr>                   
      </thead>
      <tbody>
        <?php  $i = 0; ?>
        @if($mantenimiento->direcciones->count() > 0)
          @foreach($mantenimiento->direcciones as $direccion)
            <tr>
              <td>{{$i+=1}}</td>
              <td>
                {{$direccion->Direccion}}
              </td>
              <td>{{$direccion->Barrio}}</td>
              <td> Lat:{{$direccion->Latitud}}, Lon:{{$direccion->Longitud}}</td>
              <td>
              @if($mantenimiento->estado != 'CERRADO')
                @permission('mantenimientos-direcciones-editar')
                <button class="btn btn-xs btn-primary" onclick="editarDireccion('{{$mantenimiento_id}}', '{{$direccion->DirId}}')" data-toggle="modal" data-target="#addDireccion">
                  <i class="fa fa-edit"></i>
                </button>
                @endpermission
                @permission('mantenimientos-direcciones-eliminar')
                  <form action="{{route('mantenimientos.direcciones.destroy', [$mantenimiento_id, $direccion->DirId])}}" method="post" style="display: inline-block;">
                    <input type="hidden" name="_method" value="delete">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"><i class="fa fa-trash"></i></button>
                  </form>
                @endpermission
              @endif
              </td>
            </tr>
          @endforeach
        @elseif(!empty($mantenimiento->ClienteId))
          <tr>
            <td>{{$i+=1}}</td>
            <td>
              {{$mantenimiento->cliente->DireccionDeCorrespondencia}}
            </td>
            <td>{{$mantenimiento->cliente->Barrio}}</td>
            <td> Lat:{{$mantenimiento->cliente->Latitud}}, Lon:{{$mantenimiento->cliente->Longitud}}</td>
            <td></td>
          </tr>
        @else
          <tr>
            <td colspan="5" class="text-center text-muted">NO HAY REGISTROS</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>