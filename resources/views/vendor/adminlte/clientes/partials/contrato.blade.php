<div class="col-md-12">
  <div class="panel panel-default"> 
    <div class="panel-heading">
      <h2><i class="fa fa-briefcase"></i>  Contratos
        @permission('contratos-crear')
        <div class="box-tools pull-right">
            <a  href="{{route('clientes.contratos.create', $cliente->ClienteId)}}" class="btn btn-default float-bottom btn-sm">
                <i class="fa fa-plus"></i>  <span class="hidden-xs">Agregar</span>
            </a>
        </div>
        @endpermission
      </h2> 
    </div>
    <div class="panel-body table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th style="width: 10px">#</th>
            <th>Referencia</th>
            <th>Tipo Cobro</th>
            <th>Vigencia</th>
            <th>Inicio</th>
            <th>Instalacion</th>
            <th>Finalizacion</th>
            <th>Valor</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        @foreach($cliente->contrato as $contrato)          
          <tr>
            <td>1</td>
            <td>
              @if(empty($contrato->referencia))
              <a href="{{route('clientes.contratos.show', [$cliente->ClienteId, $contrato->id])}}" target="_black">
                SIN ESPECIFICAR
              </a>              
              @else
              <a href="{{route('clientes.contratos.show', [$cliente->ClienteId, $contrato->id])}}" target="_black">
                {{$contrato->referencia}}
              </a>
              @endif
            </td>
            <td>{{$contrato->tipo_cobro}}</td>
            <td>{{$contrato->vigencia_meses}} MESES</td>
            <td>{{$contrato->fecha_inicio}}</td>
            <td>{{$contrato->fecha_instalacion}}</td>
            <td>{{$contrato->fecha_final}}</td>
            <td>${{number_format($contrato->servicio->sum('valor'), 0, ',', '.')}}</td>
            <td>
              @if($contrato->estado == 'VIGENTE')
                <span class="label label-success">{{$contrato->estado}}</span>
              @else
                <span class="label label-default">{{$contrato->estado}}</span>
              @endif
            </td>
            
            <td>
              @permission('contratos-editar')
              <a href="{{route('clientes.contratos.edit', [$cliente->ClienteId, $contrato->id])}}" class="btn btn-xs btn-primary">
                <i class="fa fa-edit"></i>
              </a>
              @endpermission
              @permission('contratos-eliminar')
              <form action="{{route('clientes.contratos.destroy', [$cliente->ClienteId, $contrato->id])}}" method="post">
                  <input type="hidden" name="_method" value="delete">
                  <input type="hidden" name="_token" value="{{csrf_token()}}">

                  <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                      <i class="fa fa-trash"></i>
                  </button>
              </form>
              @endpermission
            </td>
            
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>