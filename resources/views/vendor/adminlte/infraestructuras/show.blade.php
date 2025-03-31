@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1><i class="fa fa-plus"></i> Infraestructura - {{$infraestructura->nombre}}</h1>
@endsection

@section('main-content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border bg-blue">
                <h3 class="box-title">Detalles</h3>
                <div class="box-tools">
                    @permission('infraestructura-editar')
                    <a href="{{route('infraestructuras.edit', $infraestructura->id)}}" class="btn btn-sm btn-default"> <i class="fa fa-edit"></i> Editar</a>
                    @endpermission
                </div>
            </div>
            <div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="bg-gray">*Nombre de la Infraestructura</th>
                                <td colspan="3">
                                    <h4>{{$infraestructura->nombre}}</h4>
                                </td>
                            </tr>

                            <tr>
                                <th class="bg-gray">*Latitud</th>
                                <td>
                                    {{$infraestructura->latitud}}
                                </td>

                                <th class="bg-gray">*Longitud</th>
                                <td>
                                    {{$infraestructura->longitud}}
                                </td>
                            </tr>

                            <tr>
                                <th class="bg-gray">
                                    *Departamento
                                </th>
                                <td>
                                    {{$infraestructura->municipio->NombreDepartamento}}
                                </td>

                                <th class="bg-gray">
                                    *Municipio
                                </th>
                                <td>
                                    {{$infraestructura->municipio->NombreMunicipio}}
                                </td>

                            </tr>

                            <tr>
                                <th class="bg-gray">
                                    *Categoría
                                </th>
                                <td>
                                    {{$infraestructura->categoria}}
                                </td>

                                <th class="bg-gray">
                                    *Tipos de Categoría
                                </th>
                                <td>
                                    {{$infraestructura->tipo_categoria}}
                                </td>

                            </tr>

                            <tr>
                                <th class="bg-gray">
                                    *Dirección
                                </th>
                                <td>
                                    {{$infraestructura->direccion}}
                                </td>


                                <th class="bg-gray">
                                    *Estado
                                </th>
                                <td>
                                    {{$infraestructura->estado}}
                                </td>
                            </tr>

                            <tr>
                                <th class="bg-gray">
                                    Proveedor
                                </th>
                                <td>
                                    @if (isset($infraestructura->proveedor))
                                    <a href="{{url('/proveedores').'?identificacion='.$infraestructura->proveedor->identificacion}}">{{$infraestructura->proveedor->nombre}}</a>
                                    @endif
                                </td>
                                <th class="bg-gray">
                                    Infraestructura Padre
                                </th>
                                <td>
                                    @if (isset($infraestructura->padre))
                                    <a href="{{route('infraestructuras.show', $infraestructura->padre->id)}}">{{$infraestructura->padre->nombre}}</a>
                                    @endif
                                </td>

                            </tr>

                            <tr>
                                <th class="bg-gray">Descripción</th>
                                <td colspan="3">
                                    {{$infraestructura->descripcion}}
                                </td>
                            </tr>

                            <tr>
                                <th class="bg-gray">Datos Ubicación</th>
                                <td colspan="3">
                                    {{$infraestructura->datos_ubicacion}}
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <br>

                    <div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 no-padding">
                        <!-- required for floating -->
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tabs-left">
                            <li class="active">
                                <a href="#propiedades" data-toggle="tab">
                                    <i class="fa fa fa-check-square-o"></i> <span class="hidden-xs hidden-sm hidden-md">Propiedades</span>
                                </a>
                            </li>

                            <li>
                                <a href="#contactos" data-toggle="tab">
                                    <i class="fa fa-users"></i> <span class="hidden-xs hidden-sm hidden-md">Contactos</span>
                                </a>
                            </li>

                            <li>
                                <a href="#proyectos" data-toggle="tab">
                                    <i class="fa fa fa-cubes"></i> <span class="hidden-xs hidden-sm hidden-md">Proyectos</span>
                                </a>
                            </li>

                            <li>
                                <a href="#equipos" data-toggle="tab">
                                    <i class="fa fa-hdd-o"></i> <span class="hidden-xs hidden-sm hidden-md">Equipos</span>
                                </a>
                            </li>

                            <li>
                                <a href="#dependientes" data-toggle="tab">
                                    <i class="fa fa-desktop"></i> <span class="hidden-xs hidden-sm hidden-md">Nodos Dependientes</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-xs-10 col-sm-10  col-md-10 col-lg-10">
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="propiedades">
                                @include('adminlte::infraestructuras.partials.propiedades.show')
                            </div>
                            <div class="tab-pane" id="contactos">
                                @include('adminlte::infraestructuras.partials.contactos.show')
                            </div>
                            <div class="tab-pane" id="proyectos">
                                @include('adminlte::infraestructuras.partials.proyectos.show')
                            </div>
                            <div class="tab-pane" id="equipos">
                                @include('adminlte::infraestructuras.partials.equipos.show')
                            </div>
                            <div class="tab-pane" id="dependientes">
                                @include('adminlte::infraestructuras.partials.dependientes.show')
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

@permission('infraestructura-propiedades-crear')
@include('adminlte::infraestructuras.partials.propiedades.add')
@endpermission

@permission('infraestructura-contactos-crear')
@include('adminlte::infraestructuras.partials.contactos.add')
@endpermission

@permission('infraestructura-proyectos-crear')
@include('adminlte::infraestructuras.partials.proyectos.add')
@endpermission

@permission('infraestructura-equipos-crear')
@include('adminlte::infraestructuras.partials.equipos.add')
@endpermission

@section('mis_scripts')
<script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipios3.js')}}"></script>

<script type="text/javascript" src="/js/infraestructuras/submodulos.js"></script>

<script type="text/javascript">
    const form = $('#addEquipos form');
    form.find('select[name=codigo]').on("change", function() {
        form.find('input[name="serial"]').val("");
        form.find('input[name="serial"]').parent().removeClass('has-error');
        form.find('input[name="serial"]').parent().removeClass('has-success');
        form.find('input[name="serial"]').attr('readonly', false);
        form.find('#btn-guardar').attr('disabled', true);

    })
    form.find('input[name="serial"]').on("focusout", function() {

        const codigoInsumo = form.find('select[name=codigo]').val();
        const serial = $(this).val();

        if (codigoInsumo != '') {

            var parametros = {
                serial: serial,
                codigo_insumo: codigoInsumo,
                '_token': $('input:hidden[name=_token]').val()
            };

            $.post("/inventarios/validar/ajax", parametros, function(data) {

                if (data.resultado == true) {
                    form.find('input[name="serial"]').parent().addClass('has-success');
                    form.find('input[name="serial"]').parent().removeClass('has-error');
                    form.find('input[name="serial"]').attr('readonly', true);
                    form.find('#btn-guardar').attr('disabled', false);

                } else {
                    form.find('input[name="serial"]').parent().addClass('has-error');
                    form.find('input[name="serial"]').parent().find('.help-block').append("<strong>" + data.resultado + "</strong>");

                    toastr.options.positionClass = 'toast-bottom-right';
                    toastr.warning(data.resultado);
                }
            });
        }
    });

    async function copyName(id) {
        const campo_pwd = $('#campo_password_'+id);
        const input = campo_pwd.find('input');
        const icon = campo_pwd.find('i');
        await navigator.clipboard.writeText(input.val());
        icon.removeClass('fa-copy');
        icon.addClass('fa-check');
        setTimeout(() => {
            icon.removeClass('fa-check');;
            icon.addClass('fa-copy');
        }, "2000");
    }
</script>

@endsection
@endsection