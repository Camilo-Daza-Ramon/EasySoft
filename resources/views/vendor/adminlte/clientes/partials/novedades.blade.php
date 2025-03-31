<div class="col-md-12">
  <div class="panel panel-default"> 
    <div class="panel-heading">
      <h2><i class="fa fa-exclamation-circle"></i>  Novedades
      </h2> 
    </div>
    <div class="panel-body table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th style="width: 10px">#</th>
            <th>Concepto</th>
            <th>Cantidad</th>
            <th>Valor Unidad</th>
            <th>Uni.Medida</th>
            <th>IVA</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Estado</th>
            <th>Usuario</th>
          </tr>
        </thead>
        <tbody>
        @foreach($cliente->novedad as $novedad)          
          <tr>
            <td>
              <a href="#" data-toggle="modal" data-target="#vernovedad" data-id="{{$novedad->id}}">{{$novedad->id}}</a>
            </td>
            <td>{{$novedad->concepto}}</td>
            <td>
              {{$novedad->cantidad}}
            </td>
            <td>${{number_format($novedad->valor_unidad, 2, ',', '.')}}</td>
            <td>{{$novedad->unidad_medida}}</td>
            <td>{{number_format($novedad->iva,0,'','')}}%</td>
            <td>{{$novedad->fecha_inicio}}</td>
            <td>{{$novedad->fecha_fin}}</td>
            
            <td>
              @if($novedad->estado == 'PENDIENTE')
                <span class="label label-warning">{{$novedad->estado}}</span>
              @else
                <span class="label label-default">{{$novedad->estado}}</span>
              @endif
            </td>
            <td>
              @if(isset($novedad->user))
              {{$novedad->user->name}}
              @endif
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>