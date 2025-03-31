  <!--
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{$tickets_abiertos}}</h3>

        <p>Tickets Abiertos</p>
      </div>
      <div class="icon">
        <i class="fa fa-wrench"></i>
      </div>
      <a href="{{route('tickets.index')}}" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  -->
  <!-- ./col -->
  @if(isset($mantenimientos_pendiente))
  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{$mantenimientos_pendiente}}</h3>

        <p>Mantenimientos Pendientes</p>
      </div>
      <div class="icon">
        <i class="fa fa-wrench"></i>
      </div>
      <a href="{{route('correctivos.index')}}?estado=ABIERTO" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  @endif
  <!-- ./col -->

  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{$pqrs_pendientes}}</h3>

        <p>PQRS Pendientes</p>
      </div>
      <div class="icon">
        <i class="fa fa-support"></i>
      </div>
      <a href="/pqr?estado=ABIERTO" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <!--
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{$clientes_aprovicionar}}</h3>

        <p>Aprovicionamientos</p>
      </div>
      <div class="icon">
        <i class="fa fa-hdd-o"></i>
      </div>
      <a href="{{ url('aprovisionar') }}" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  -->
  <!-- ./col -->

  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{$clientes_activos}}</h3>

        <p>Clientes Activos</p>
      </div>
      <div class="icon">
        <i class="fa fa-wifi"></i>
      </div>
      <a href="{{route('clientes.index')}}?estado=ACTIVO" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->

  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{$instalaciones_pendientes}}</h3>

        <p>Instalaciones Pendientes</p>
      </div>
      <div class="icon">
        <i class="fa fa-users"></i>
      </div>
      <a href="{{route('clientes.index')}}?estado=EN INSTALACION" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->

  <!--
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{$suspenciones}}</h3>

        <p>Suspenciones</p>
      </div>
      <div class="icon">
        <i class="fa fa-download"></i>
      </div>
      <a href="{{route('clientes.index')}}?accion=SUSPENDER" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>-->
  <!-- ./col -->



  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{$clientes_suspendidos}}</h3>

        <p>Clientes Suspendidos</p>
      </div>
      <div class="icon">
        <i class="fa fa-user-times"></i>
      </div>
      <a href="{{route('clientes.index')}}?accion=SUSPENDIDOS" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->

  <!-- 
  <div class="col-lg-3 col-xs-6">
    <div class="small-box bg-aqua">
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

  <div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
      <div class="inner">
        <h3>{{$solicitudes}}</h3>

        <p>Solicitudes Pendientes</p>
      </div>
      <div class="icon">
        <i class="fa fa-exclamation-triangle"></i>
      </div>
      <a href="{{route('atencion-clientes.estadisticas')}}" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->