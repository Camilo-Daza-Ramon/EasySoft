@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-chain-broken"></i>  Suspensiones Temporales</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form action="{{route('suspensiones-temporales.index')}}" role="search" method="GET">                               
                            
                            <div class="btn-group pull-right">
                                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span id="icon-opciones" class="fa fa-gears"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    @permission('suspensiones-temporales-crear')
                                        <li>
                                            <a href="#" data-toggle="modal" data-target="#addSuspension">
                                                <i class="fa fa-plus"></i>  Agregar
                                            </a>
                                        </li>
                                    @endpermission
                                    @permission('suspensiones-temporales-exportar')
                                        <li><a href="#" id="exportar"><i class="fa fa-file-excel-o"></i> Exportar</a></li>
                                    @endpermission
                                </ul>
                            </div>
                            

                            <div class="row">
                                <div class="col-md-8">

                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <input type="text" class="form-control" name="palabra" placeholder="Buscar" value="{{(isset($_GET['palabra'])? $_GET['palabra']:'')}}" autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="estado" id="estado">
                                                <option value="">Elija un estado</option>
                                                @foreach($estados as $estado)
                                                    @if(isset($_GET['estado']))
                                                        @if($_GET['estado'] == $estado)
                                                            <option value="{{$estado}}" selected>{{$estado}}</option>
                                                        @else
                                                            <option value="{{$estado}}">{{$estado}}</option>
                                                        @endif
                                                    @else
                                                        <option value="{{$estado}}">{{$estado}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i>  Buscar</button>
                                        </div>

                                    </div>
                                </div>

                                
                            </div>

                        </form>                        
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-hover">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Cedula</th>
                                <th>Nombre</th>
                                <th>Fecha Inicio</th>                                
                                <th>Fecha Fin</th>
                                <th>Creado Por</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>
                            <tbody>
                                @if($suspensiones->count() > 0)
                                    @foreach($suspensiones as $suspension)          
                                    <tr>
                                        <td>{{$suspension->id}}</td>
                                        <td>
                                            <a href="#" data-toggle="modal" data-target="#showSuspension" data-id="{{$suspension->id}}">{{$suspension->cliente->Identificacion}}</a>
                                        </td>
                                        <td>{{$suspension->cliente->NombreBeneficiario}} {{$suspension->cliente->Apellidos}}</td>
                                        <td>{{date('Y-m-d H:i:s', strtotime($suspension->fecha_hora_inicio))}}</td>
                                        <td>{{date('Y-m-d H:i:s', strtotime($suspension->fecha_hora_fin))}}</td>
                                        <td>{{$suspension->user->name}}</td>
                                        <td>
                                            @if($suspension->estado == 'PENDIENTE')                                                 
                                                <label class="label label-warning">{{$suspension->estado}}</label>
                                            @elseif($suspension->estado == 'ACTIVA')
                                                <label class="label label-success">{{$suspension->estado}}</label>
                                            @elseif($suspension->estado =='CANCELADA')
                                                <label class="label label-danger">{{$suspension->estado}}</label>
                                            @else
                                                <label class="label label-default">{{$suspension->estado}}</label>
                                            @endif                                            
                                        </td>
                                        <td>
                                            @if($suspension->estado == 'PENDIENTE' || $suspension->estado == 'ACTIVA')
                                                @permission('suspensiones-temporales-editar')
                                                    <a class="btn btn-primary btn-xs" title="Editar" data-toggle="modal" data-target="#editSuspension" data-id="{{$suspension->id}}"><i class="fa fa-edit"></i></a>
                                                @endpermission

                                                @if($suspension->estado == 'PENDIENTE')
                                                    @permission('suspensiones-temporales-cancelar')
                                                    <form style="display: inline-block;" action="{{route('suspensiones-temporales.destroy', $suspension->id)}}" method="post">
                                                        <input type="hidden" name="_method" value="delete">
                                                        <input type="hidden" name="_token" value="{{csrf_token()}}">

                                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de cancelar?');" title="Cancelar">
                                                            <i class="fa fa-ban"></i>   
                                                        </button>
                                                    </form>
                                                    @endpermission
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No hay registros</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$suspensiones->currentPage()}} de {{$suspensiones->lastPage()}}. Total registros {{$suspensiones->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $suspensiones->appends(Request::only(['palabra','estado']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('adminlte::atencion-clientes.suspensiones-temporales.show')

    @permission('suspensiones-temporales-crear')
        @include('adminlte::atencion-clientes.suspensiones-temporales.create')
    @endpermission

    @permission('suspensiones-temporales-editar')
        @include('adminlte::atencion-clientes.suspensiones-temporales.edit')
    @endpermission



    @section('mis_scripts')
        <script>

            toastr.options.positionClass = 'toast-bottom-right';
            let total_tiempo = 0;
            
            const buscarCliente = () => {

                if($('input[name="numero_cedula"]').val().length > 0){

                    $('#btn-enviar-form').attr('disabled', false);

                    var parametros = {
                        cedula : $('input[name="numero_cedula"]').val(),
                        '_token' : $('input:hidden[name=_token]').val()
                    };

                    const modal = $('#addSuspension');

                    $.post('/suspensiones-temporales/ajax', parametros).done(function(data){
                        if (Object.keys(data).length > 0) {

                            total_tiempo = parseInt(data.total_tiempo);

                            modal.find('#nombretxt').text(data.nombre);
                            modal.find('#diastxt').text(total_tiempo);
                            modal.find('input[name="cliente_id"]').val(data.cliente_id);

                            if(data.error.length > 0) {

                                $('#panel-formulario').hide(1000);
                                toastr.error(data.error);

                            }else{

                                modal.find('input[name="numero_cedula"]').attr('readonly', true);
                                modal.find('#panel-formulario').show(1000);
                                modal.find('#btn-enviar-form').attr('disabled', false);
                            }

                        }else{
                            modal.find('#panel-formulario').hide(1000);
                            toastr.error('Cliente no existe!');
                        }
                    });
                }else{
                    toastr.warning("Ingrese un numero válido.");
                }

            }


            const establecerFechaLimite = (modal, input) => {

                let fechaBase;

                modal.find('input[name="fecha_fin"]').val('');

                if(input != null){
                    fechaHoy = new Date(input.val() + "-01");
                    modal.find('input[name="fecha_fin"]').attr('min', input.val() + "-01");
                }else{
                    fechaHoy = new Date();
                }
                
                fechaHoy.setDate(fechaHoy.getDate() + total_tiempo);

                const fechaFormateada = fechaHoy.toISOString().slice(0, 10);

                modal.find('input[name="fecha_fin"]').attr('max', fechaFormateada);
            }

            $('#addSuspension').on('hidden.bs.modal', function (e) {

                const modal = $(this);

                modal.find('#panel-formulario').hide(1000);

                modal.find('#form_add_suspension').get(0).reset();
                modal.find('input[name="numero_cedula"]').attr('readonly', false);
                modal.find('input[name="numero_cedula"]').val('');
                modal.find('#nombretxt').text('');
                modal.find('#diastxt').text('');

                $('#btn-enviar-form').attr('disabled', true);

            });


            $('#showSuspension').on('show.bs.modal', function (event) {

                var a = $(event.relatedTarget) // Button that triggered the modal
                var id = a.data('id');
                var url = '/suspensiones-temporales/'+id;
                var modal = $(this);

                $.get(url,null, function(data){

                    let fecha1 = new Date((data.suspension['fecha_hora_inicio']).slice(0, -4));
                    let fecha2 = new Date((data.suspension['fecha_hora_fin']).slice(0, -4));

                    let diferenciaMs = fecha2 - fecha1;

                    let diferenciaDias = diferenciaMs / (1000 * 60 * 60 * 24);

                    
                    modal.find('#txt-cedula').text(data.cliente['Identificacion']);
                    modal.find('#txt-nombre').text(data.cliente['NombreBeneficiario'] + ' ' + data.cliente['Apellidos']);
                    modal.find('#txt-usuario').text(data.usuario['name']);
                    modal.find('#txt-fecha_inicio').text((data.suspension['fecha_hora_inicio']).slice(0, -4));
                    modal.find('#txt-fecha_fin').text((data.suspension['fecha_hora_fin']).slice(0, -4));
                    modal.find('#txt-fecha_solicitud').text(data.suspension['fecha_solicitud']);
                    modal.find('#txt-dias_solicitados').text(Math.round(diferenciaDias));
                    modal.find('#txt-descripcion').text(data.suspension['descripcion']);
                });

            });

            $('#editSuspension').on('show.bs.modal', function (event) {

                var a = $(event.relatedTarget) // Button that triggered the modal
                var id = a.data('id');
                var url = '/suspensiones-temporales/'+id;
                var modal = $(this);

                $.get(url+'/edit',null, function(data){

                    const cliente = data.cliente;
                    const suspension = data.suspension;                                       

                    modal.find('form').attr('action', url);
                    
                    modal.find('textarea[name="descripcion"]').val(suspension['descripcion']);
                    modal.find('input[name=mes_inicio]').val((suspension['fecha_hora_inicio']).slice(0, 7));
                    
                    total_tiempo = data.total_tiempo;
                    establecerFechaLimite(modal, modal.find('input[name=mes_inicio]'));

                    modal.find('input[name=fecha_fin]').val((suspension['fecha_hora_fin']).slice(0, 10));
                    modal.find('input[name=fecha_solicitud]').val(suspension['fecha_solicitud']);

                    modal.find('select[name=estado] option[value='+suspension.estado+']').prop("selected", true);

                    modal.find('input[name=cedula]').val(cliente['Identificacion']);
                    modal.find('input[name=nombre]').val(cliente['NombreBeneficiario'] + ' ' + cliente['Apellidos']);

                    if(suspension.estado == 'ACTIVA'){
                        modal.find('input[name=mes_inicio]').attr('readonly', true);
                    }else{
                        modal.find('input[name=mes_inicio]').attr('readonly', false);
                    }

                });
            });

        </script>
        @permission('suspensiones-temporales-exportar')
        <script type="text/javascript">
            $('#exportar').on('click',function(){
                var parametros = {
                    palabra : "{!! (isset($_GET['palabra'])? $_GET['palabra']: '') !!}",
                    estado : "{!! (isset($_GET['estado'])? $_GET['estado']: '') !!}",
                    '_token' : $('input:hidden[name=_token]').val() 
                }

                $('#opciones').attr('disabled',true);
                $('#icon-opciones').removeClass('fa-gears');
                $('#icon-opciones').addClass('fa-refresh fa-spin');
        

                $.ajax({
                    type: "POST",
                    url: '/suspensiones-temporales/exportar',
                    data: parametros,
                    xhrFields: {
                        responseType: 'blob' // to avoid binary data being mangled on charset conversion
                    },
                    success: function(blob, status, xhr) {
                        // check for a filename
                        var filename = "";
                        var disposition = xhr.getResponseHeader('Content-Disposition');
                        if (disposition && disposition.indexOf('attachment') !== -1) {
                            var filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/;
                            var matches = filenameRegex.exec(disposition);
                            if (matches != null && matches[1]) filename = matches[1].replace(/['"]/g, '');
                        }

                        if (typeof window.navigator.msSaveBlob !== 'undefined') {
                            // IE workaround for "HTML7007: One or more blob URLs were revoked by closing the blob for which they were created. These URLs will no longer resolve as the data backing the URL has been freed."
                            window.navigator.msSaveBlob(blob, filename);
                        } else {
                            var URL = window.URL || window.webkitURL;
                            var downloadUrl = URL.createObjectURL(blob);

                            if (filename) {
                                // use HTML5 a[download] attribute to specify filename
                                var a = document.createElement("a");
                                // safari doesn't support this yet
                                if (typeof a.download === 'undefined') {
                                    window.location.href = downloadUrl;
                                } else {
                                    a.href = downloadUrl;
                                    a.download = filename;
                                    document.body.appendChild(a);
                                    a.click();
                                }
                            } else {
                                window.location.href = downloadUrl;
                            }

                            setTimeout(function () { URL.revokeObjectURL(downloadUrl); }, 100); // cleanup
                        }

                        $('#opciones').attr('disabled',false);
                        $('#icon-opciones').removeClass('fa-refresh fa-spin');
                        $('#icon-opciones').addClass('fa-gears');
                    },
                    error: function(blob, status, xhr){
                        toastr.options.positionClass = 'toast-bottom-right';
                        toastr.error(xhr);

                        $('#opciones').attr('disabled',false);
                        $('#icon-opciones').removeClass('fa-refresh fa-spin');
                        $('#icon-opciones').addClass('fa-gears');
                    }
                });
            });
        </script>
        @endpermission
    @endsection
@endsection