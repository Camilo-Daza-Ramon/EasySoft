<div class="col-md-12">
  <div class="panel panel-default"> 
    <div class="panel-heading">
      <h2><i class="fa fa-smile-o"></i>  Atencion Cliente
      </h2> 
    </div>
    <div class="panel-body table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th style="width: 10px">#</th>
            <th>Motivo</th>
            <th>Categoria</th>
            <th>Agente</th>
            <th>Fecha</th>
            <th>Medio</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        @foreach($cliente->atencion_cliente as $atencion)          
          <tr>
            <td>{{$atencion->id}}</td>
            <td>
              @if(!empty($atencion->motivo_atencion_id))
                {{$atencion->motivo_atencion->motivo}}
              @endif
            </td>
            <td>
              @if(!empty($atencion->motivo_atencion_id))
                {{$atencion->motivo_atencion->categoria}}
              @else
                {{$atencion->punto_atencion_cliente->motivo_categoria}}
              @endif
            </td>
            <td>
              @if(!empty($atencion->user_id))
                {{$atencion->user->name}}
              @endif
            </td>
            <td>{{date('Y-m-d h:i:s', strtotime($atencion->fecha_atencion_agente))}}</td>
            <td>{{$atencion->medio_atencion}}</td>
            
            <td>
              @if($atencion->estado == 'PENDIENTE')
                <span class="label label-warning">{{$atencion->estado}}</span>
              @elseif($atencion->estado == 'ABANDONO')
                <span class="label label-default">{{$atencion->estado}}</span>
              @else
                <span class="label label-success">{{$atencion->estado}}</span>
              @endif
            </td>
            <td>
              @if($atencion->estado != 'PENDIENTE')
                <button type="button" class="btn btn-success btn-xs" onclick="traer_atencion({!!$atencion->id!!});return false;"> <i class="fa fa-eye"></i></button>
              @endif
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>    
  </div>
</div>
@include('adminlte::clientes.partials.atencion-cliente.show')
