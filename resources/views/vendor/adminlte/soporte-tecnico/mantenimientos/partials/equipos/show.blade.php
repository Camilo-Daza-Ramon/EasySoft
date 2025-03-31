<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-hdd-o"></i>  Equipos
    @if($mantenimiento->estado != 'CERRADO')
      <div class="btn-group pull-right">
        <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
          <span id="icon-opciones" class="fa fa-gears"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
          @permission('mantenimientos-equipos-crear')
          <li>
            <a data-id_mantenimiento="{{$mantenimiento_id}}" href="#" id="btn-add-equipo" data-toggle="modal" data-target="#addEquipo">
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
          <th>Equipo</th>
          <th>Serial</th>
          <th>Cambio</th>
          <th>Observaciones</th>
          <th>Acciones</th>
        </tr>                   
      </thead>
      <tbody>
        @if($mantenimiento->equipos->count() > 0)
          <?php  $i = 0; ?>
          @foreach($mantenimiento->equipos as $equipo)
          <tr>
            <td>{{$i+=1}}</td>
            <td>{{$equipo->Equipo}} - {{$equipo->MarcaReferencia}}</td>
            <td>{{$equipo->Serial}}</td>
            <td>{{$equipo->RealizoCambio}}</td>
            <td>{{$equipo->Observaciones}}</td>
            <td>
            @if($mantenimiento->estado != 'CERRADO')
              @permission('mantenimientos-equipos-editar')
              <button class="btn btn-xs btn-primary" onclick="editarEquipo('{{$mantenimiento_id}}', '{{$equipo->EqId}}')" data-toggle="modal" data-target="#addEquipo">
                <i class="fa fa-edit"></i>
              </button>
              @endpermission
              @permission('mantenimientos-equipos-eliminar')
                <form action="{{ route('mantenimientos.equipos.destroy', ['mantenimiento' => $mantenimiento_id, 'equipo' => $equipo->EqId]) }}" method="post" style="display: inline-block;">
                  <input type="hidden" name="_method" value="delete">
                  <input type="hidden" name="link" value="{{(isset($link))? $link : ''}}">

                  <input type="hidden" name="_token" value="{{csrf_token()}}">
                  <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar"><i class="fa fa-trash"></i></button>
                </form>
              @endpermission
            @endif
            </td>
          </tr>
          @endforeach
        @else
          <tr>
            <td colspan="6" class="text-center text-muted">NO HAY REGISTROS</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>