@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1><i class="fa fa-plus"></i> Editar Infraestructura</h1>
@endsection

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border bg-blue">
                <h3 class="box-title">Detalles</h3>
            </div>
            <form id="form-edit-infra" action="" method="post">
                {{csrf_field()}}
                <input type="hidden" name="_method" value="PUT">

                <!-- /.box-header -->
                @include('adminlte::infraestructuras.partials.form')

                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary pull-right">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('mis_scripts')
<script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipios3.js')}}"></script>
<script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipios2.js')}}"></script>

<script type="text/javascript">
    const infra = {!!json_encode($infraestructura) !!};
    const form = $('#form-edit-infra');


    form.find('input[name=nombre]').val(infra.nombre);
    form.find('input[name=latitud]').val(infra.latitud);
    form.find('input[name=longitud]').val(infra.longitud);
    form.find('input[name=latitud]').val(infra.latitud);
    form.find('select[name=departamento]').val(infra.municipio.DeptId);

    buscarmunicipios(infra.municipio_id,
        form.find('select[name=departamento]'), form.find('select[name=municipio]'));

    form.find('select[name=municipio]').val(infra.municipio_id);
    form.find('select[name=categoria]').val(infra.categoria);
    form.find('select[name=tipo_categoria]').val(infra.tipo_categoria);
    form.find('input[name=direccion]').val(infra.direccion);
    form.find('select[name=estado]').val(infra.estado);
    form.find('select[name=proveedor]').val(infra.proveedor_id);
    form.find('select[name=infraestructura_id]').val(infra.infraestructura_id);
    form.find('textarea[name=descripcion]').val(infra.descripcion);
    form.find('textarea[name=datos_ubicacion]').val(infra.datos_ubicacion);
    form.attr('action', "{{route('infraestructuras.update', $infraestructura->id)}}");

</script>
@endsection
@endsection