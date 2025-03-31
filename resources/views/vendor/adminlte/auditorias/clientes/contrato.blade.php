<div class="col-md-12">
  <div class="panel panel-default"> 
    <div class="panel-heading">
      <h2><i class="fa fa-briefcase"></i>  Contratos</h2> 
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
              <a href="{{route('clientes.contratos.show', [$contrato->ClienteId,$contrato->id])}}" target="_black">
                SIN ESPECIFICAR
              </a>              
              @else
              <a href="{{route('clientes.contratos.show', [$contrato->ClienteId,$contrato->id])}}" target="_black">
                {{$contrato->referencia}}
              </a>
              @endif
            </td>
            <td>{{$contrato->tipo_cobro}}</td>
            <td>{{$contrato->vigencia_meses}} MESES</td>
            <td>{{$contrato->fecha_inicio}}</td>
            <td>{{$contrato->fecha_instalacion}}</td>
            <td>${{number_format($contrato->servicio->sum('valor'), 0, ',', '.')}}</td>
            <td>
              @if($contrato->estado == 'VIGENTE')
                <span class="label label-success">{{$contrato->estado}}</span>
              @else
                <span class="label label-default">{{$contrato->estado}}</span>
              @endif
            </td>            
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>    
  </div>
</div>