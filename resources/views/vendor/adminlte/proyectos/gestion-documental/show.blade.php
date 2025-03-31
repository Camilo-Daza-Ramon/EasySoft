@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1><i class="fa fa-gavel"></i> Información Documental </h1>
@endsection

@section('main-content')
<div class="container-fluid spark-screen">
    <div class="row">
        <div class="col-md-4">
            <div class="box box-primary">
                <div class="box-header with-border bg-blue">
                    <h3 class="box-title"><i class="fa fa-gavel"></i> Inf. Documental</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>PROYECTO:</b> <a href="{{route('proyectos.show', $documental_proyecto->proyecto_id)}}#gestionDocumental"
                                class="pull-right">{{ isset($documental_proyecto->proyecto) ? $documental_proyecto->proyecto->NumeroDeProyecto : $proyecto}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>CATEGORIA:</b> <a class="pull-right">{{$documental_proyecto->nombre}}</a>
                        </li>
                        <li class="list-group-item">
                            <b>TIPO:</b> <a class="pull-right">{{$documental_proyecto->tipo}}</a>
                        </li>
                        @if(!empty($periodo))
                        <li class="list-group-item">
                            <b>PERIODO:</b> <a class="pull-right">{{strtoupper(strftime('%B %Y', strtotime($periodo->periodo)))}}</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            @if($documental_proyecto->tipo == 'VERSION' || !empty($_GET['periodo']))
                <div class="box box-primary">
                    <div class="box-header with-border bg-blue">
                        <h3 class="box-title"><i class="fa fa-list"></i> {{$documental_proyecto->tipo}}ES </h3>
                        <div class="btn-group pull-right">
                            @permission('documental-versiones-crear')
                            <a type="button" class="btn btn-default btn-xs" href="#" data-toggle="modal"
                                data-target="#versionAdd">
                                <i class="fa fa-plus"></i> Agregar</a>
                            @endpermission
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @include('adminlte::proyectos.gestion-documental.versiones.index')
                    </div>
                    <!-- /.box-body -->
                </div>

            @elseif($documental_proyecto->tipo == 'MENSUAL')
                <div class="box box-primary">
                    <div class="box-header with-border bg-blue">
                        <h3 class="box-title"><i class="fa fa-calendar"></i> {{$documental_proyecto->tipo}}ES </h3>
                        <div class="btn-group pull-right">
                            @permission('documental-mensuales-crear')
                            <a type="button" class="btn btn-default btn-xs" href="#" data-toggle="modal"
                                data-target="#mensualAdd">
                                <i class="fa fa-plus"></i> Agregar</a>
                            @endpermission
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @include('adminlte::proyectos.gestion-documental.mensuales.index')
                    </div>
                    <!-- /.box-body -->
                </div>
            @endif
        </div>
    </div>

    @permission('documental-versiones-crear')
    @include('adminlte::proyectos.gestion-documental.versiones.create')
    @endpermission

    @permission('documental-versiones-editar')
    @include('adminlte::proyectos.gestion-documental.versiones.edit')
    @endpermission

    @permission('documental-mensuales-crear')
    @include('adminlte::proyectos.gestion-documental.mensuales.create')
    @endpermission

    @permission('documental-mensuales-editar')
    @include('adminlte::proyectos.gestion-documental.mensuales.edit')
    @endpermission


    @permission('documental-versiones-archivos-crear')
    @include('adminlte::proyectos.gestion-documental.archivos.create')
    @endpermission

    @permission('documental-versiones-archivos-ver')
    @include('adminlte::proyectos.gestion-documental.versiones.show')
    @endpermission

    @include('adminlte::partials.modal_show_archivos')

    @section('mis_scripts')
        <script type="text/javascript" src="/js/myfunctions/show-archivo.js"></script>

        @permission('documental-versiones-archivos-crear')
            <script>
                $('#archivoAdd').on('show.bs.modal', function(event) {
                    var a = $(event.relatedTarget) // Button that triggered the modal
                    var version = a.data('id');
                    var documental = a.data('documental');

                    //var empresa = a.data('empresa');
                    var url = `/documental-proyectos/${documental}/versiones/${version}/archivos`;
                    var modal = $(this);
                    modal.find('form').attr('action', url);
                });

                const formulario_archivo = $('#archivoAdd').find('form');

                formulario_archivo.on('submit', function(){
                    $(this).find('button[type="submit"]').attr('disabled',true);
                    $(this).find('button i').addClass('fa fa-refresh fa-spin');
                });
            </script>
        @endpermission

        @permission('documental-versiones-archivos-ver')
        <script>
            $('#versionShow').on('show.bs.modal', function(event) {
                
                var a = $(event.relatedTarget) // Button that triggered the modal
                var titulo = a.data('titulo');
                var version = a.data('id');
                var documental = a.data('documental');
                
                var url = `/documental-proyectos/${documental}/versiones/${version}`;
                var modal = $(this);
                modal.find('#titulo').text(titulo);

                var tabla = modal.find('table tbody');

                tabla.empty();

                $.get(url, null, function(data) {
                    
                    $.each(data.archivos, function(index, archivoObj) {

                        let link = '';

                        if(archivoObj.tipo == 'zip' || archivoObj.tipo == 'rar'){
                            link = `<a href="/storage/${archivoObj.ruta}" target="_blank">${archivoObj.nombre}</a>`;
                        }else{
                            link = `<a href="#" id="archivo-${archivoObj.id}" data-toggle="modal" data-target="#modal-attachment" data-tipo="${archivoObj.tipo}" data-archivo="/storage/${archivoObj.ruta}">${archivoObj.nombre}</a>`;
                        }

                        var fila = `
                        <tr>
                            <td>${link}</td>
                            <td>${archivoObj.tipo}</td>
                            <td>
                                <form action="/documental-proyectos/${documental}/versiones/${version}/archivos/${archivoObj.id}" method="post">
                                    <input type="hidden" name="_method" value="delete">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                    <i class="fa fa-trash"></i> </button>
                                </form>
                            </td>
                        </tr>`;
                        tabla.append(fila)

                    });

                });
            });
        </script>
        @endpermission
        
        @permission('documental-versiones-ver')
            <script>
                $('#archivoAdd').on('show.bs.modal', function(event) {
                    var a = $(event.relatedTarget) // Button that triggered the modal
                    var titulo = a.data('titulo');
                    var version = a.data('id');
                    var documental = a.data('documental');

                    //var empresa = a.data('empresa');
                    var url = `/documental-proyectos/${documental}/versiones/${version}/archivos `;
                    var modal = $(this);
                    modal.find('form').attr('action', url);
                    
                });   
            </script>
        @endpermission

        @permission('documental-versiones-editar')
        <script>
                $('#versionEdit').on('show.bs.modal', function(event) {
                    
                    var a = $(event.relatedTarget) // Button that triggered the modal
                    var version = a.data('id');
                    var documental = a.data('documental');
                    
                    var url = `/documental-proyectos/${documental}/versiones/${version}`;
                    var modal = $(this);
                    modal.find('form').trigger('reset');

                    $.get(url + '/edit', null, function(data) {

                        modal.find('form').attr('action', url);
                        
                        $.each(data, function(index, versionObj) {
                            modal.find('input[name="titulo"]').val(versionObj.titulo);
                            modal.find('input[name="version"]').val(versionObj.version);
                            modal.find('input[name="fecha_desde"]').val(versionObj.fecha_desde);
                            modal.find('input[name="fecha_hasta"]').val(versionObj.fecha_hasta);
                            modal.find('select[name=estado] option[value='+versionObj.estado+']').prop("selected", true);
                        });
                        
                    });
                });
            </script>
        @endpermission

        @permission('documental-mensuales-editar')
        <script>
            $('#mensualEdit').on('show.bs.modal', function(event) {
                
                var a = $(event.relatedTarget) // Button that triggered the modal
                var id = a.data('id');
                var documental = a.data('documental');
                
                var url = `/documental-proyectos/${documental}/mensuales/${id}`;
                var modal = $(this);
                modal.find('form').trigger('reset');

                $.get(url + '/edit', null, function(data) {

                    modal.find('form').attr('action', url);
                    $.each(data, function(index, mensualObj) {
                        console.log(mensualObj.periodo);

                        modal.find('input[name="periodo"]').val((mensualObj.periodo).substring(0, 7));                           
                    });
                    
                });
            });
        </script>
        @endpermission

    @endsection
 @endsection