<div class="panel panel-default">
  <div class="panel-heading bg-blue">
    <h2 class="panel-title"><i class="fa fa-clock"></i>  Paradas de Reloj
      <div class="btn-group pull-right">
      </div>
    </h2>
  </div> 
  <div class="panel-body table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Fecha Inicio</th>
          <th>Fecha Fin</th>
          <th>Descripcion</th>
        </tr>                   
      </thead>
      <tbody>
        @if($pqr->paradas_reloj->count() > 0)
            @foreach($pqr->paradas_reloj as $parada_reloj)
            <tr>
                <td>{{$parada_reloj->ParadaId}}</td>
                <td>{{$parada_reloj->InicioParadaDeReloj .' '. $parada_reloj->InicioParada}}</td>
                <td>{{$parada_reloj->FinParadaDeReloj .' '. $parada_reloj->FinParada}}</td>
                <td>{{$parada_reloj->DescripcionParada}}</td>
            </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4" class="text-center">NO HAY REGISTROS</td>
            </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>