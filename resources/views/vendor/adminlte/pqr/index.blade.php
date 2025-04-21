@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-comments-o"></i>  PQRS</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form id="form-buscar" action="{{route('pqr.index')}}" role="search" method="GET">  
                            
                            <div class="btn-group pull-right">
                                <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    <span id="icon-opciones" class="fa fa-gears"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    @permission('pqrs-crear')
                                    <li><a href="{{route('pqr.create')}}"><i class="fa fa-plus"></i> Crear</a></li>
                                    @endpermission



                                    @permission('pqrs-exportar')
                                    <li><a href="#" id="exportar"><i class="fa fa-file-excel-o"></i> Exportar</a></li>
                                    @endpermission

                                </ul>
                            </div>
                            <div class="row">
                                <div class="col-md-10 col-sm-11 col-xs-10">
                                    <div class="row">
                                        <div class="form-group col-md-3 col-sm-6">
                                            <input type="number" class="form-control" name="documento" placeholder="Número documento" value="{{(isset($_GET['documento'])? $_GET['documento']:'')}}" autocomplete="off">                                            
                                        </div>

                                        <div class="form-group col-md-3 col-sm-6">
                                            <input type="text" class="form-control" name="cun" placeholder="Número CUN" value="{{(isset($_GET['cun'])? $_GET['cun']:'')}}" autocomplete="off">
                                        </div>                                      

                                        <div class="form-group col-md-3 col-sm-6">
                                            <select class="form-control" name="estado" id="estado">
                                                <option value="">Elija un estado</option>
                                                @foreach($estados as $estado)

                                                    <option value="{{$estado->Status}}" {{(isset($_GET['estado'])) ? (($_GET['estado'] == $estado->Status) ? 'selected' : '') : ''}}>{{$estado->Status}}</option>
                                                @endforeach
                                                
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                    @auth 
                                        @if(!in_array(auth()->user()->id, [274, 275]))
                                            <div class="form-group col-md-3 col-sm-6">
                                                <select class="form-control" name="proyecto" id="proyecto">
                                                    <option value="">Elija un proyecto</option>
                                                    @foreach($proyectos as $proyecto)

                                                        <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    @endauth 

                                        <div class="form-group col-md-3 col-sm-6">
                                            <select class="form-control" name="departamento" id="departamento">
                                                <option value="">Elija un departamento</option>
                                                @foreach($departamentos as $departamento)
                                                    <option value="{{$departamento->DeptId}}" {{(isset($_GET['departamento'])) ? (($_GET['departamento'] == $departamento->DeptId) ? 'selected' : '') : ''}}>{{$departamento->NombreDelDepartamento}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3 col-sm-6">
                                            <select class="form-control" name="municipio" id="municipio">
                                                <option value="">Elija un municipio</option>
                                            </select> 
                                        </div>

                                        <div class="col-md-3 col-sm-6">
                                            <button type="submit" class="btn btn-default btn-block"> <i class="fa fa-search"></i>  Buscar</button>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>                              
                        </form>
                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">CUN</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Fecha Reporte</th>
                                    <th scope="col">Fecha Cierre</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Dias sin Resolver</th>                                    
                                    <th>Acciones</th>
                                </tr>

                                @foreach($pqrs as $pqr)

                                <?php 
                                    $contador = date_diff(date_create($pqr->FechaApertura), date_create($pqr->FechaCierre));
                                    $total_dias = $contador->days;
                                ?>
                                <tr>
                                    <th>
                                        @if($pqr->Status == 'CERRADO')
                                            <i class="fa fa-circle text-gray"></i>
                                        @elseif($total_dias >= 15)
                                            <i class="fa fa-circle text-red"></i>
                                        @elseif($total_dias >= 10 && $total_dias < 15)
                                            <i class="fa fa-circle text-yellow"></i>
                                        @elseif($total_dias < 10)
                                            <i class="fa fa-circle text-default"></i>
                                        @endif
                                    </th>
                                    <th>
                                        <a href="{{route('pqr.show', $pqr->PqrId)}}">
                                            @if(empty($pqr->CUN))
                                                {{$pqr->IdentificacionCliente}}
                                            @else
                                                {{$pqr->CUN}}
                                            @endif
                                        </a>
                                    </th>
                                   
                                    <td>
                                        @if(isset($pqr->municipio))
                                            {{$pqr->municipio->NombreMunicipio}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($pqr->municipio))
                                            {{$pqr->municipio->departamento->NombreDelDepartamento}}
                                        @endif
                                        </td>
                                    <td>
                                        {{date('Y-m-d', strtotime($pqr->FechaApertura))}}
                                    </td>
                                    <td>
                                        @if(!empty($pqr->FechaCierre))
                                          {{date('Y-m-d', strtotime($pqr->FechaCierre))}}
                                        @endif
                                    </td>
                                    
                                    <td>
                                        {{$pqr->Status}}
                                    </td>

                                    <td>
                                        {{$total_dias}}
                                    </td>
                                    
                                    <td>
                                        @permission('pqrs-editar')
                                        <a href="{{route('pqr.edit', $pqr->PqrId)}}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                                        @endpermission    

                                        @if($pqr->Status == 'CERRADO')
                                            @permission('pqrs-actas-descargar')
                                            <a href="{{route('pqrs.acta', $pqr->PqrId)}}" class="btn btn-xs btn-success" title="Descargar Acta Traslado"><i class="fa fa-download"></i></a>
                                            @endpermission
                                        @endif

                                        @if($pqr->Status != 'CERRADO')
                                        
                                            @permission('pqrs-eliminar')
                                            <form action="{{route('pqr.destroy', $pqr->PqrId)}}" style="display:inline-block;" method="post">
                                                <input type="hidden" name="_method" value="delete">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Estas seguro de eliminar?');" title="Eliminar">
                                                    <i class="fa fa-trash-o"></i>   
                                                </button>
                                            </form>
                                            @endpermission

                                        @endif
                                    </td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$pqrs->currentPage()}} de {{$pqrs->lastPage()}}. Total registros {{$pqrs->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $pqrs->appends(Request::only(['proyecto','departamento','municipio','estado','CUN']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @section('mis_scripts')

    <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>
        
        <script type="text/javascript">
            $(document).ready(function(){
                buscar_departamentos({{(isset($_GET['departamento']) ? $_GET['departamento'] : '')}});
                buscar_municipio({{(isset($_GET['municipio']) ? $_GET['municipio'] : '')}});
            });
        </script>

    <script type="text/javascript">
        $('#exportar').on('click',function(){
            var parametros = {
                identificacion : "{!! (isset($_GET['documento'])? $_GET['documento']:'') !!}",
                cun : "{!! (isset($_GET['cun'])? $_GET['cun']:'') !!}",
                proyecto : "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                estado : "{!! (isset($_GET['estado'])? $_GET['estado']:'') !!}",
                '_token' : $('input:hidden[name=_token]').val() 
            }

            $('#opciones').attr('disabled',true);
            $('#icon-opciones').removeClass('fa-gears');
            $('#icon-opciones').addClass('fa-refresh fa-spin');

            $.ajax({
                type: "POST",
                url: '/pqrs/exportar',
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