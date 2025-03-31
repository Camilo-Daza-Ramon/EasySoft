<div class="row">

  <div class="col-lg-3 col-xs-6">

    <div class="small-box bg-green">
      <div class="inner">
        <h3>{{$estados_contratos['VIGENTE']}}</h3>
        <p>Vigentes</p>
      </div>
      <div class="icon">
        <i class="ion fa fa-check-square-o"></i>
      </div>
      <a href="?estado=VIGENTE" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <div class="col-lg-3 col-xs-6">

    <div class="small-box bg-yellow">
      <div class="inner">
        <h3>{{$estados_contratos['PENDIENTE']}}</h3>
        <p>Pendientes</p>
      </div>
      <div class="icon">
        <i class="ion fa fa-hourglass-2"></i>
      </div>
      <a href="?estado=PENDIENTE" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>

  <div class="col-lg-3 col-xs-6">

    <div class="small-box bg-red">
      <div class="inner">
        <h3>{{$estados_contratos['ANULADO']}}</h3>
        <p>Anulados</p>
      </div>
      <div class="icon">
        <i class="ion fa fa-ban"></i>
      </div>
      <a href="?estado=ANULADO" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>  

  <div class="col-lg-3 col-xs-6">

    <div class="small-box bg-default">
      <div class="inner">
        <h3>{{$estados_contratos['FINALIZADO']}}</h3>
        <p>Finalizados</p>
      </div>
      <div class="icon">
        <i class="ion fa fa-file-text-o"></i>
      </div>
      <a href="?estado=FINALIZADO" class="small-box-footer">Más info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>

</div>