@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-users"></i>  Clientes - Cambios y Reemplazos</h1>
@endsection

@section('mis_styles')
<link rel="stylesheet" type="text/css" href="https://adminlte.io/themes/AdminLTE/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form class="navbar-form navbar-left" action="{{route('cambios-reemplazos.index')}}" role="search" method="GET">
                            <div class="form-group">
                                <input type="number" class="form-control" name="documento" id="documento" placeholder="Documento" value="{{(isset($_GET['documento'])? $_GET['documento']:'')}}" autocomplete="off">

                                <select class="form-control" name="proyecto" id="proyecto">
                                    <option value="">Elija un proyecto</option>
                                    @foreach($proyectos as $proyecto)

                                        <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                    @endforeach
                                </select>

                                <select class="form-control" name="departamento" id="departamento">
                                    <option value="">Elija un departamento</option>
                                    @foreach($departamentos as $departamento)
                                        <option value="{{$departamento->DeptId}}">{{$departamento->NombreDelDepartamento}}</option>
                                    @endforeach
                                </select>

                                <select class="form-control" name="municipio" id="municipio">
                                    <option value="">Elija un municipio</option>
                                </select>

                                <select class="form-control" name="meta">
                                    <option value="">Elija una meta</option>
                                    @foreach($metas as $meta)
                                        <option value="{{$meta->META}}">{{$meta->META}}</option>
                                    @endforeach
                                </select>

                                <select class="form-control" name="estado">
                                    <option value="">Elija un estado</option>
                                    <option value="PENDIENTE">PENDIENTE</option>
                                    <option value="REEMPLAZADO">REEMPLAZADO</option>
                                </select>

                            </div>
                            <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i>  Buscar</button>
                        </form>
                        
                        <div class="box-tools pull-right">
                            @permission('cambios-reemplazos-exportar')
                            <div class="btn-group">
                                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span id="icon-opciones" class="fa fa-gears"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#" id="exportar"><i class="fa fa-file-excel-o"></i> Exportar</a></li>
                                </ul>
                            </div>
                            @endpermission
                        </div>                       

                        
                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>
                                    <th scope="col">Documento</th> 
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Departamento</th>
                                    <th>Estado</th>
                                    <th scope="col">Reemplazado por</th>
                                    <th>Acciones</th>
                                </tr>
                                @foreach($cambios_reemplazos as $dato)
                                <tr>
                                    <td>{{$dato->Identificacion}}</td>

                                    <td>{{mb_convert_case($dato->nombre, MB_CASE_TITLE, "UTF-8")}}</td>
                                    <td>{{$dato->NombreMunicipio}}</td>
                                    <td>{{$dato->NombreDepartamento}}</td>
                                    <td>
                                        {{$dato->Status}}
                                    </td>

                                    <td>
                                        {{$dato->reemplazado_por}}
                                    </td>
                                    <td>
                                        @if(!empty($dato->reemplazado_por))
                                            <a class="btn btn-success btn-xs" href="{{route('cambios-reemplazos.show',$dato->id)}}"><i class="fa fa-eye"></i></a>
                                            @permission('cambios-reemplazos-actualizar')
                                                <a class="btn btn-primary btn-xs" href="{{route('cambios-reemplazos.edit',$dato->id)}}"><i class="fa fa-edit"></i></a>
                                            @endpermission
                                            
                                            @permission('cambios-reemplazos-eliminar')
                                                <form action="{{route('cambios-reemplazos.destroy', $dato->id)}}" method="post" style="display:inline-block;">
                                                    <input type="hidden" name="_method" value="delete">
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                                                    <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                                        <i class="fa fa-trash-o"></i>   
                                                    </button>
                                                </form>
                                            @endpermission

                                        @else
                                            @permission('cambios-reemplazos-crear')
                                            <button class="btn btn-primary btn-xs" title="Reemplazar" data-toggle="modal" data-target="#addReemplazo" data-id="{{$dato->ClienteId}}" data-documento="{{$dato->Identificacion}}" data-nombre="{{$dato->nombre}}" data-municipio="{{$dato->NombreMunicipio}} - {{$dato->NombreDepartamento}}" data-estado="{{$dato->Status}}" data-municipio_id="{{$dato->municipio_id}}" data-proyecto_id="{{$dato->ProyectoId}}" data-meta_cliente="{{$dato->meta_cliente}}" data-reemplazo_id="{{$dato->id}}"><i class="fa fa-retweet"></i></button>
                                            @endpermission

                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$cambios_reemplazos->currentPage()}} de {{$cambios_reemplazos->lastPage()}}. Total registros {{$cambios_reemplazos->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $cambios_reemplazos->appends(Request::only(['documento', 'municipio', 'estado', 'meta']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @permission('cambios-reemplazos-crear')
        @include('adminlte::cambios-reemplazos.partials.add')
    @endpermission

    @section('mis_scripts')
        <script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="/js/dataTables.bootstrap.min.js"></script>
        <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>
        <script type="text/javascript" src="/js/myfunctions/exportar_ajax.js"></script>
    
        <script type="text/javascript">
            $(document).ready(function(){
                buscar_departamentos({{(isset($_GET['departamento']) ? $_GET['departamento'] : '')}});
                buscar_municipio({{(isset($_GET['municipio']) ? $_GET['municipio'] : '')}});
            });
        </script>

        @permission('cambios-reemplazos-exportar')
        <script type="text/javascript">
            $('#exportar').on('click',function(){
                var parametros = {               
                    '_token' : $('input:hidden[name=_token]').val(),
                    documento: "{!! (isset($_GET['documento'])? $_GET['documento']:'') !!}",
                    proyecto: "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                    departamento: "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                    municipio: "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                    meta: "{!! (isset($_GET['meta'])? $_GET['meta']:'') !!}",
                    estado: "{!! (isset($_GET['estado'])? $_GET['estado']:'') !!}",
                }

                exportarConAjax('/cambios-reemplazos/exportar', parametros);

               
            });

            
        </script>
        @endpermission

        @permission('cambios-reemplazos-crear')
            <script type="text/javascript">
                var id, municipio_id, proyecto_id, cliente_id, contrato_saliente_id;

                $('#addReemplazo').on('show.bs.modal', function (event) {
                    var a = $(event.relatedTarget) // Button that triggered the modal
                    id = a.data('id');
                    var cedula = a.data('documento'); // Extract info from data-* attributes
                    var nombre = a.data('nombre');
                    var municipio = a.data('municipio');
                    var estado = a.data('estado');

                    $('input:hidden[name=meta_cliente_id]').val(a.data('meta_cliente'));
                    $('input:hidden[name=reemplazo_id]').val(a.data('reemplazo_id'));

                    municipio_id = a.data('municipio_id');
                    proyecto_id = a.data('proyecto_id');
                      
                    var modal = $(this)
                    modal.find('#cedula').text(cedula);
                    modal.find('#nombre').text(nombre);
                    modal.find('#municipio').text(municipio);
                    modal.find('#estado').text(estado);
                    cliente_id = id;

                    $('#siguiente').attr('onclick', 'next("Antiguo");');
                });
                

                function traer_contrato(){

                    if($('input[name="contrato"]:checked').length == 0){
                        toastr.options.positionClass = 'toast-bottom-right';
                        toastr.warning("Debe elegir un contrato");
                    }else{

                        $('#contrato-antiguo').text($('input[name="contrato"]:checked').val());
                        $('input:hidden[name=contrato_a_id]').val($('input[name="contrato"]:checked').val());

                        

                        var parametros = {
                            municipio_id : municipio_id,
                            proyecto_id : proyecto_id,
                            contrato_saliente_id : contrato_saliente_id,
                            '_token' : $('input:hidden[name=_token]').val()
                        }

                        
                        

                        $('#tabla-clientes').DataTable( {
                            "pageLength": 5,
                            language: {
                                url: 'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json'
                            },
                            "ordering": false,
                            "ajax": {
                                "url": "/cambios-reemplazos/ajax",
                                "type": "POST",
                                "data": parametros,
                                "dataSrc": function ( json ) {

                                    if(jQuery.isEmptyObject(json.data)){
                                        toastr.options.positionClass = 'toast-bottom-right';
                                        toastr.warning("No hay clientes");
                                    }else{
                                        var data = [];
                                        for (var i = 0; i <= (json.data.length - 1); i++) {
                                            var items = {};

                                            items.input = '<input type="radio" name="cliente" value="'+json.data[i]['ClienteId']+'" required>';
                                            items.Identificacion = json.data[i]['Identificacion'];
                                            items.nombre = json.data[i]['nombre'];
                                            items.municipio = json.data[i]['municipio'];
                                            items.Status = json.data[i]['Status'];
                                            items.ClienteId = json.data[i]['ClienteId'];

                                            data.push(items);
                                        }
                                       
                                        //Make your callback here.
                                        $('#contrato').hide(1000);
                                        $('#clientes').show(1000);
                                        $('#siguiente').attr('onclick', 'next("Nuevo");');

                                        return data;
                                    }                                
                                }
                            },
                            "columns": [
                                { "data": "input" },
                                { "data": "Identificacion" },
                                { "data": "nombre" },
                                { "data": "municipio" },
                                { "data": "Status" }
                            ],
                            "createdRow": function( row, data, dataIndex ) {
                                console.log(data);                          
                                $(row).attr('id', 'client-'+data['ClienteId'] );                            
                            }
                        });                    
                    }                
                }

                function next(tipo){

                    if (tipo =="Nuevo") {
                        cliente_id = $('input[name="cliente"]:checked').val();
                        $('input:hidden[name=cliente_n_id]').val(cliente_id);

                        $('#cedula-n').text($('#client-' + cliente_id).find('td').eq(1).text());
                        $('#nombre-n').text($('#client-' + cliente_id).find('td').eq(2).text());
                        $('#municipio-n').text($('#client-' + cliente_id).find('td').eq(3).text());
                        $('#estado-n').text($('#client-' + cliente_id).find('td').eq(4).text());
                        
                    }

                    if(cliente_id == null){
                        toastr.options.positionClass = 'toast-bottom-right';
                        toastr.warning("Debe elegir un cliente");
                    }else{ 

                        var parametros = {
                            cliente_id : cliente_id,
                            '_token' : $('input:hidden[name=_token]').val()
                        }

                        $.post('/contratos/ajax', parametros).done(function(data){

                            $('#lista-contrato').empty();

                            if(!jQuery.isEmptyObject(data)){
                                $.each(data, function(index, contratoObj){
                                    contrato_saliente_id = contratoObj.id;
                                  $('#lista-contrato').append(`<tr>
                                                                    <td>
                                                                        <input type="radio" name="contrato" value="${contratoObj.id}" required>
                                                                    </td>
                                                                    <td>${contratoObj.id}</td>
                                                                    <td>${contratoObj.fecha_instalacion}</td>
                                                                    <td>${(contratoObj.fecha_operacion != null)? contratoObj.fecha_operacion : ''}</td>
                                                                    <td>${contratoObj.fecha_final}</td>
                                                                    <td>${contratoObj.tipo_cobro}</td>
                                                                    <td>${contratoObj.vigencia_meses}</td>
                                                                    <td>
                                                                        <span class="badge bg-default">$${Number(contratoObj.total).toFixed(0).replace(/\d(?=(\d{3})+\.)/g, '$&,')}</span>
                                                                    </td>
                                                                    <td>${contratoObj.estado}</td>
                                                                </tr>`);
                                });

                                $('#tipo').text(tipo);

                                if (tipo == 'Antiguo') {
                                    $('#cliente').hide(1000);
                                    $('#contrato').show(1000);                
                                    $('#siguiente').attr('onclick', 'traer_contrato();');
                                }else{
                                    $('#clientes').hide(1000);
                                    $('#contrato').show(1000);
                                    $('#siguiente').attr('onclick', 'resumen();');                                
                                }

                            }else{
                                toastr.options.positionClass = 'toast-bottom-right';
                                toastr.warning("No hay datos del contrato");
                            }
                        }).fail(function(e){
                            toastr.options.positionClass = 'toast-bottom-right';
                            toastr.error(e.statusText);
                        });
                    }
                }

                function resumen(){
                    if($('input[name="contrato"]:checked').length == 0){
                        toastr.options.positionClass = 'toast-bottom-right';
                        toastr.warning("Debe elegir un contrato.");
                    }else{                    
                        $('#contrato-n').text($('input[name="contrato"]:checked').val());
                        $('input:hidden[name=contrato_n_id]').val($('input[name="contrato"]:checked').val());

                        $('#dato-contrato-antiguo').show();
                        $('#title-resumen').show();
                        $('#contrato').hide(1000);
                        $('#cliente').show(1000);
                        $('#resumen').show(1000);

                        $('#siguiente').hide();
                        $('#confirmar').show();                    
                    }                
                }

                $('#form-cambios-reemplazos-store').on('submit', function () {
                    $('#confirmar').attr("disabled", "true");                    
                }); 

            </script>
        @endpermission
    @endsection     
@endsection