@if ( (isset($_GET['cedula']) && Auth::user()->roles()->count() > 1 ) || !isset($_GET['cedula']) )

@permission(['dashboard-noc', 'dashboard-admin'])
<!-- Cards en comun de los roles noc - admin -->

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

<div class="col-lg-3 col-xs-6">
    <div class="small-box bg-green">
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

@permission('reporte-onts-listar')
<div class="col-lg-3 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-red">
        <div class="inner">
            <h3>{{$reportes_onts_fallidas}}</h3>

            <p>ONTs Fallidas</p>
        </div>
        <div class="icon">
            <i class="fa fa-exclamation-triangle"></i>
        </div>
        <a href="{{route('red.reporte.onts')}}" class="small-box-footer">Más información <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
@endpermission

@endpermission

@permission(['dashboard-noc', 'dashboard-comercial', 'dashboard-admin'])
<!-- Cards en comun de los roles noc - comercial - admin -->
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
</div>
@endpermission
@endif