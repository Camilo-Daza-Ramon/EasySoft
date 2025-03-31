@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-hdd-o"></i>  Atencion al Cliente</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form action="{{route('atencion-clientes.index')}}" role="search" method="GET">

                            
                                    
                               
                            
                            <div class="btn-group pull-right">
                                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span id="icon-opciones" class="fa fa-gears"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    @permission('atencion-clientes-crear')
                                    <li>
                                        <a href="{{route('atencion-clientes.create')}}">
                                            <i class="fa fa-plus"></i>  Agregar
                                        </a>
                                    </li>
                                    @endpermission
                                    @permission('atencion-clientes-exportar')
                                    <li><a href="#" id="exportar"><i class="fa fa-file-excel-o"></i> Exportar</a></li>
                                    @endpermission
                                </ul>
                            </div>
                            

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <input type="number" class="form-control" name="cedula" placeholder="Número documento" value="{{(isset($_GET['cedula'])? $_GET['cedula']:'')}}" autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="departamento" id="departamento">
                                                <option value="">Elija un departamento</option>
                                                @foreach($departamentos as $departamento)
                                                <option value="{{$departamento->DeptId}}" {{(isset($_GET['departamento'])) ? (($_GET['departamento'] == $departamento->DeptId) ? 'selected' : '') : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                                                @endforeach                                                
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <select class="form-control" name="municipio" id="municipio">
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <select name="categorias" id="categorias" class="form-control">
                                                <option value="">Elija una categoria</option>
                                                @foreach($categorias as $categoria)
                                                <option value="{{$categoria->categoria}}">{{$categoria->categoria}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <select name="motivo" id="motivo" class="form-control" ></select>

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
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i>  Buscar</button>
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
                                <th>Municipio</th>
                                <th>Categoria</th>
                                <th>Motivo</th>
                                
                                <th>Agente</th>
                                <th>Fecha</th>
                                <th>Medio</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach($atenciones as $atencion)          
                                  <tr>
                                    <th>
                                        @if(Auth::user()->hasRole('asesor-punto-atencion'))
                                            <a href="{{route('atencion-clientes.atender', $atencion->id)}}">{{$atencion->punto_atencion_cliente->turno}}</a>
                                        @else
                                            {{$atencion->id}}
                                        @endif
                                    </th>
                                    <td>
                                        @if(isset($atencion->cliente))
                                            <a href="{{route('clientes.show', $atencion->cliente->ClienteId)}}" target="_blanck">{{$atencion->cliente->Identificacion}}</a>
                                        @else
                                            {{$atencion->identificacion}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($atencion->municipio_id))
                                            {{$atencion->municipio->NombreMunicipio}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($atencion->motivo_atencion_id))
                                            {{$atencion->motivo_atencion->categoria}}
                                        @else
                                            {{$atencion->punto_atencion_cliente->motivo_categoria}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($atencion->motivo_atencion_id))
                                            {{$atencion->motivo_atencion->motivo}}
                                        @endif
                                    </td>
                                    
                                    <td>
                                        @if(!empty($atencion->user_id))
                                            {{mb_convert_case($atencion->user->name, MB_CASE_TITLE, "UTF-8")}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($atencion->fecha_atencion_agente))
                                            {{date('Y-m-d H:i:s', strtotime($atencion->fecha_atencion_agente))}}
                                        @endif
                                    </td>
                                    <td>{{$atencion->medio_atencion}}</td>
                                    
                                    <td>
                                      @if($atencion->estado == 'PENDIENTE')
                                        <span class="label label-warning">{{$atencion->estado}}</span>
                                      @elseif($atencion->estado == 'ABANDONO')
                                        <span class="label label-default">{{$atencion->estado}}</span>
                                      @else
                                        <span class="label label-success">{{$atencion->estado}}</span>
                                      @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-success btn-xs" onclick="traer_atencion({!!$atencion->id!!});return false;"> <i class="fa fa-eye"></i></button>
                                    </td>
                                  </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$atenciones->currentPage()}} de {{$atenciones->lastPage()}}. Total registros {{$atenciones->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $atenciones->appends(Request::only(['departamento','municipio','categorias','motivo', 'estado']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('adminlte::clientes.partials.atencion-cliente.show')

    @section('mis_scripts')
        <script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipios2.js')}}"></script>
        <script type="text/javascript" src="js/atencion-cliente/show.js"></script>
        <script type="text/javascript" src="js/atencion-cliente/motivos.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                buscarmunicipios({{(isset($_GET['municipio']) ? $_GET['municipio'] : '')}});
            });

            $("#departamento").change(function() {
                buscarmunicipios($('#municipio').val());
            });
        </script>

        @permission('atencion-clientes-exportar')
        <script type="text/javascript">
            $('#exportar').on('click',function(){
                var parametros = {
                    municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']: '') !!}",
                    departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']: '') !!}",
                    categorias : "{!! (isset($_GET['categorias'])? $_GET['categorias']: '') !!}",
                    motivo : "{!! (isset($_GET['motivo'])? $_GET['motivo']: '') !!}",
                    estado : "{!! (isset($_GET['estado'])? $_GET['estado']: '') !!}",
                    '_token' : $('input:hidden[name=_token]').val() 
                }

                $('#opciones').attr('disabled',true);
                $('#icon-opciones').removeClass('fa-gears');
                $('#icon-opciones').addClass('fa-refresh fa-spin');
        

                $.ajax({
                    type: "POST",
                    url: '/atencion-clientes/exportar',
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