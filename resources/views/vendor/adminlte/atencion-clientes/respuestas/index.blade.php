@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-thumbs-o-up"></i>  Respuestas</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form action="{{route('encuestas-respuestas.index')}}" role="search" method="GET">        
                            <div class="btn-group pull-right">
                                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span id="icon-opciones" class="fa fa-gears"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">                                    
                                    @permission('encuestas-respuestas-exportar')
                                    <li><a href="#" id="exportar"><i class="fa fa-file-excel-o"></i> Exportar</a></li>
                                    @endpermission
                                </ul>
                            </div>
                            

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <input type="text" class="form-control" name="palabra" placeholder="Palabra clave" value="{{(isset($_GET['palabra'])? $_GET['palabra']:'')}}" autocomplete="off">
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
                                            <select name="tipo" id="tipo" class="form-control">
                                                <option value="">Elija una tipo</option>
                                                @foreach($tipos as $tipo)
                                                <option value="{{$tipo}}" {{(isset($_GET['tipo'])) ? (($_GET['tipo'] == $tipo) ? 'selected' : '') : ''}}>{{$tipo}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <input type="date" class="form-control" name="desde" value="{{(isset($_GET['desde'])? $_GET['desde']:'')}}" autocomplete="off">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <input type="date" class="form-control" name="hasta" value="{{(isset($_GET['hasta'])? $_GET['hasta']:'')}}" autocomplete="off">
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
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>
                                    <th scope="col">Documento</th>
                                    <th scope="col" style="width:50%;">Pregunta</th>
                                    <th scope="col">Respuesta</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">ID ATENCION</th>
                                </tr>
                                @foreach($respuestas as $respuesta)
                                <tr>
                                    <td>
                                        @if(isset($respuesta->atencion_cliente))
                                            {{$respuesta->atencion_cliente->identificacion}}
                                        @else
                                            @if(isset($respuesta->cliente))
                                                <a href="{{route('clientes.show', $respuesta->cliente->ClienteId)}}">{{$respuesta->cedula}}</a>
                                            @else
                                                {{$respuesta->cedula}}
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        {{$respuesta->encuesta->descripcion}}
                                    </td>
                                    <td>
                                        <?php

                                        if($respuesta->respuesta > 0){
                                            for ($i = 0; $i < $respuesta->respuesta; $i++) { 
                                                echo '<i class="fa fa-star text-yellow"></i>';
                                            }
                                        }else{
                                            echo '<i class="fa fa-star-o"></i>';
                                        }
                                            
                                        ?>
                                    </td>
                                    <td>                                        

                                        @if($respuesta->tipo == 'LLAMADA')
                                            <span class="label bg-teal" title="{{$respuesta->tipo}}"><i class="fa fa-phone"></i></span>
                                        @else
                                            <span class="label bg-teal" title="{{$respuesta->tipo}}"><i class="fa fa-male"></i></span>
                                        @endif
                                    </td>                                  
                                    <td>{{date('Y-m-d H:i:s', strtotime($respuesta->fecha))}}</td>
                                    <td>{{$respuesta->identificador}}</td>                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$respuestas->currentPage()}} de {{$respuestas->lastPage()}}. Total registros {{$respuestas->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $respuestas->appends(Request::only(['municipio', 'desde', 'hasta', 'tipo']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('adminlte::clientes.partials.atencion-cliente.show')

    @section('mis_scripts')

    <script type="text/javascript" src="{{asset('js/myfunctions/buscarmunicipios2.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function(){
                buscarmunicipios({{(isset($_GET['municipio']) ? $_GET['municipio'] : '')}});
            });

            $("#departamento").change(function() {
                buscarmunicipios($('#municipio').val());
            });        
    </script>
    <script type="text/javascript">
            $('#exportar').on('click',function(){
                var parametros = {
                    municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']: '') !!}",
                    tipo : "{!! (isset($_GET['tipo'])? $_GET['tipo']: '') !!}",
                    palabra : "{!! (isset($_GET['palabra'])? $_GET['palabra']: '') !!}",
                    desde : "{!! (isset($_GET['desde'])? $_GET['desde']: '') !!}",
                    hasta : "{!! (isset($_GET['hasta'])? $_GET['hasta']: '') !!}",
                    '_token' : $('input:hidden[name=_token]').val() 
                }

                $('#opciones').attr('disabled',true);
                $('#icon-opciones').removeClass('fa-gears');
                $('#icon-opciones').addClass('fa-refresh fa-spin');
        

                $.ajax({
                    type: "POST",
                    url: '/encuestas-respuestas/exportar',
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
    @endsection
@endsection