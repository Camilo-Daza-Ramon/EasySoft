  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{$clientes_auditar}}</h3>

        <p>Auditar</p>
      </div>
      <div class="icon">
        <i class="fa fa-tasks"></i>
      </div>
      <a href="{{route('clientes.index')}}?estado=PENDIENTE" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-green">
      <div class="inner">
        <h3>{{$clientes_aprovicionar}}</h3>

        <p>Aprovicionamientos (Comercial) </p>
      </div>
      <div class="icon">
        <i class="fa fa-hdd-o"></i>
      </div>
      <a href="{{route('clientes.index')}}?estado=APROBADO" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <!--
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-yellow">
      <div class="inner">
        <h3>{{$reactivaciones}}</h3>

        <p>Reactivaciones</p>
      </div>
      <div class="icon">
        <i class="fa fa-upload"></i>
      </div>
      <a href="{{route('clientes.index')}}?accion=REACTIVAR" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  -->
  <!-- ./col -->
  <!--
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-red">
      <div class="inner">
        <h3>{{$suspenciones}}</h3>

        <p>Suspenciones</p>
      </div>
      <div class="icon">
        <i class="fa fa-download"></i>
      </div>
      <a href="{{route('clientes.index')}}?accion=SUSPENDER" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  -->
  <!-- ./col -->