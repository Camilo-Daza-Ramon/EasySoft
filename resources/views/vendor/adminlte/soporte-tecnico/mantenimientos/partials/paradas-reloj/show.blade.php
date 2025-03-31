<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-clock-o"></i>  Paradas de Reloj

    @if($mantenimiento->estado != 'CERRADO')
      <div class="btn-group pull-right">
        <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
          <span id="icon-opciones" class="fa fa-gears"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
          @permission('mantenimientos-paradas-reloj-crear')
          <li>
            <a data-id_mantenimiento="{{$mantenimiento_id}}" id="btn-add-parada-reloj" href="#" data-toggle="modal" data-target="#addParadaReloj">
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
          <th>Descripcion</th>
          <th>Fecha Hora Inicio</th>
          <th>Fecha Hora Fin</th>
          <th>Acciones</th>
        </tr>                   
      </thead>
      <tbody>
        @if($mantenimiento->paradas_reloj->count() > 0)
          <?php  $i = 0; ?>
          @foreach($mantenimiento->paradas_reloj as $parada_reloj)
          <tr>
            <td>{{$i+=1}}</td>
            <td><p class="text-lowercase">{{$parada_reloj->DescripcionParada}}</p></td>
            <td>{{$parada_reloj->InicioParadaDeReloj}} {{$parada_reloj->HoraInicio}}:{{$parada_reloj->MinInicio}}</td>
            <td>{{$parada_reloj->FinParadaDeReloj}} {{$parada_reloj->HoraFin}}:{{$parada_reloj->MinFin}}</td>
            <td>
            @if($mantenimiento->estado != 'CERRADO')
              @permission('mantenimientos-paradas-reloj-editar')
              <button class="btn btn-xs btn-primary" onclick="editarParadaReloj('{{$mantenimiento_id}}', '{{$parada_reloj->ParadaId}}')" data-toggle="modal" data-target="#addParadaReloj">
                <i class="fa fa-edit"></i>
              </button>
              @endpermission
              @permission('mantenimientos-paradas-reloj-eliminar')
                <form action="{{route('mantenimientos.paradas-reloj.destroy', [$mantenimiento_id, $parada_reloj->ParadaId])}}" method="post" style="display: inline-block;">
                  <input type="hidden" name="_method" value="delete">
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
            <td colspan="5" class="text-center text-muted">NO HAY REGISTROS</td>
          </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>