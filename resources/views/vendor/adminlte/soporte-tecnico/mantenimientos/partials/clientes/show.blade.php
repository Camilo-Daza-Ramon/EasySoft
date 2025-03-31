<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-users"></i>  Clientes Afectados      
      <div class="btn-group pull-right">
        <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
          <span id="icon-opciones" class="fa fa-gears"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">

          @if($mantenimiento->estado != 'CERRADO')
            @permission('mantenimientos-clientes-crear')
            <li>
              <a href="#" data-toggle="modal" data-target="#addCliente">
                <i class="fa fa-plus"></i>  Agregar
              </a>
            </li>
            @endpermission
          @endif
          
          @if(($mantenimiento->TipoMantenimiento == 'REDN' || $mantenimiento->TipoMantenimiento == 'REDT') && $mantenimiento->estado == 'CERRADO' && !empty($cedulas) && $indisponibilidad['compensar'])
            @permission('mantenimientos-novedades-crear')            
            <li>
              <a href="#" data-toggle="modal" data-target="#addNovedades">
                <i class="fa fa-plus"></i>  Novedades masivas
              </a>
            </li>
            @endpermission
          @endif
        </ul>
      </div>
    </h2>
  </div> 
  <div class="panel-body table-responsive">
    @if(!$indisponibilidad['compensar'] && $mantenimiento->estado == 'CERRADO')
      <div class="callout callout-warning ">
        <h4> <i class="fa fa-info-circle"></i> ATENCIÓN!</h4>
        <p>No se genera indisponibilidad debido a que no superó el tiempo maximo de atención oportuna.</p>
      </div>

    @endif
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Cedula</th>
          <th>Nombre</th>
          <th>Acciones</th>
        </tr>                   
      </thead>
      <tbody>
        <?php  $i = 0; ?>
        @if($mantenimiento->clientes->count() > 0)
          @foreach($mantenimiento->clientes as $cliente)
            <tr>
              <td>{{$i+=1}}</td>
              <td>
                <a href="{{route('clientes.show', $cliente->cliente->ClienteId)}}">{{$cliente->cliente->Identificacion}}</a>
              </td>
              <td>{{mb_convert_case($cliente->cliente->NombreBeneficiario . ' ' . $cliente->cliente->Apellidos, MB_CASE_TITLE, "UTF-8")}}</td>
              <td>
              @if($mantenimiento->estado != 'CERRADO')
                @permission('proyectos-clausulas-eliminar')
                  <form action="{{route('mantenimientos.clientes.destroy', [$mantenimiento_id, $cliente->PrManCKiD])}}" method="post" style="display: inline-block;">
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
              <a href="{{route('clientes.show', $mantenimiento->ClienteId)}}">{{$mantenimiento->cliente->Identificacion}}</a>
            </td>
            <td>{{mb_convert_case($mantenimiento->cliente->NombreBeneficiario . ' ' . $mantenimiento->cliente->Apellidos, MB_CASE_TITLE, "UTF-8")}}</td>
            <td></td>
          </tr>
        @else
          <tr>
            <td colspan="4" class="text-center text-muted">NO HAY REGISTROS</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>