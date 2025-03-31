@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-user-secret"></i> Auditar Clientes </h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form class="navbar-form navbar-left" action="{{route('auditorias.clientes.index')}}" role="search" method="GET">
                              <div class="form-group">
                                <input type="number" class="form-control" name="documento" placeholder="Número documento" value="{{(isset($_GET['documento'])? $_GET['documento']:'')}}" autocomplete="off">

                                <select class="form-control" name="proyecto" id="proyecto">
                                    <option value="">Elija un proyecto</option>
                                    @foreach($proyectos as $proyecto)
                                        <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                    @endforeach
                                </select>

                                <select class="form-control" name="departamento" id="departamento">
                                    <option value="">Elija un departamento</option>
                                </select>

                                <select class="form-control" name="municipio" id="municipio">
                                    <option value="">Elija un municipio</option>
                                </select>                                

                              </div>
                              <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i>  Buscar</button>
                        </form>
                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>                                    
                                    <th scope="col">Documento</th> 
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Departamento</th>
                                    <th scope="col">Proyecto</th>                      
                                    <th>Estado</th>
                                </tr>
                                @foreach($clientes as $dato)
                                <tr>
                                    <th><a href="{{route('auditorias.clientes.show', $dato->ClienteId)}}">{{$dato->Identificacion}}</a></th>

                                    <td>{{mb_convert_case($dato->NombreBeneficiario . ' ' . $dato->Apellidos, MB_CASE_TITLE, "UTF-8")}}</td>
                                        @if(!empty($dato->municipio))
                                            <td>{{$dato->municipio->NombreMunicipio}}</td>
                                            <td>{{$dato->municipio->departamento->NombreDelDepartamento}}</td>
                                        @else
                                            <td>{{$dato->ubicacion->municipio->NombreMunicipio}}</td>
                                            <td>{{$dato->ubicacion->municipio->departamento->NombreDelDepartamento}}</td>
                                        @endif
                                    <td>{{$dato->proyecto->NumeroDeProyecto}}</td>

                                    <td>
                                        @if($dato->Status == 'ACTIVO')
                                            {{$dato->EstadoDelServicio}}
                                        @elseif($dato->Status == 'APROBADO')
                                          <span class="label label-success">{{$dato->Status}}</span>
                                        @elseif($dato->Status == 'RECHAZADO')
                                          <span class="label label-danger">{{$dato->Status}}</span>
                                        @elseif($dato->Status == 'PENDIENTE')
                                          <span class="label label-warning">{{$dato->Status}}</span>
                                        @else
                                            {{$dato->Status}}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$clientes->currentPage()}} de {{$clientes->lastPage()}}. Total registros {{$clientes->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $clientes->appends(Request::only(['accion','estado','municipio']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @section('mis_scripts')   
    
    <script type="text/javascript">
        $(document).ready(function(){
            buscar_departamentos({{(isset($_GET['departamento']) ? $_GET['departamento'] : '')}});
            buscar_municipio({{(isset($_GET['municipio']) ? $_GET['municipio'] : '')}});
        });

        $('#proyecto').on('change', function(){            
            buscar_departamentos($('#departamento').val());            
        });


        $('#departamento').on('change', function(){            
            buscar_municipio($('#municipio').val());            
        });

        function buscar_municipio(municipio){

            var parameters = {
                departamento_id : $('#departamento').val(),
                proyecto_id : $('#proyecto').val(),
                '_token' : $('input:hidden[name=_token]').val()
            };


            $.post('/estudios-demanda/ajax-municipios', parameters).done(function(data){

                $('#municipio').empty();
                $('#municipio').append('<option value="">Elija un municipio</option>');
                $.each(data, function(index, municipiosObj){

                    if (municipio != null) {
                        if (municipiosObj.MunicipioId == municipio) {

                            $('#municipio').append('<option value="' + municipiosObj.MunicipioId + '" selected>' + municipiosObj.NombreMunicipio + '</option>');
                        }else{
                            $('#municipio').append('<option value="' + municipiosObj.MunicipioId + '">' + municipiosObj.NombreMunicipio + '</option>');
                        }
                    }else{
                        $('#municipio').append('<option value="' + municipiosObj.MunicipioId + '">' + municipiosObj.NombreMunicipio + '</option>');
                    }                                     
                });
            }).fail(function(e){
                alert('error');
            });
        }

        function buscar_departamentos(departamento){
            var parameters = {
                proyecto_id : $('#proyecto').val(),
                '_token' : $('input:hidden[name=_token]').val()
            };

            $.post('/estudios-demanda/ajax-departamentos', parameters).done(function(data){

                $('#departamento').empty();                
                $('#departamento').append('<option value="">Elija un departamento</option>');
                $.each(data, function(index, departamentosObj){

                    if (departamento != null) {
                        if (departamentosObj.DeptId == departamento) {

                            $('select[name=departamento]').append('<option value="' + departamentosObj.DeptId + '" selected>' + departamentosObj.NombreDelDepartamento + '</option>');
                        }else{
                            $('select[name=departamento]').append('<option value="' + departamentosObj.DeptId + '">' + departamentosObj.NombreDelDepartamento + '</option>');
                        }
                    }else{
                        $('select[name=departamento]').append('<option value="' + departamentosObj.DeptId + '">' + departamentosObj.NombreDelDepartamento + '</option>');
                    }                    
                });
            }).fail(function(e){
                alert('error');
            });
        }
    </script>
    @endsection     
@endsection