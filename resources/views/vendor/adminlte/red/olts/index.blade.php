@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-server"></i>  OLTs</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form id="form-buscar" class="navbar-form navbar-left" action="{{route('red.olts.index')}}" role="search" method="GET">
                              <div class="form-group">
                                <input type="text" class="form-control" name="palabra" placeholder="Nombre" value="{{(isset($_GET['palabra'])? $_GET['palabra']:'')}}" autocomplete="off">


                                <select class="form-control" name="departamento" id="departamento1">
                                    <option value="">Elija un departamento</option>
                                    @foreach($departamentos as $departamento)
                                        <option value="{{$departamento->DeptId}}">{{$departamento->NombreDelDepartamento}}</option>
                                    @endforeach
                                </select>

                                <select class="form-control" name="municipio" id="municipio1">
                                    <option value="">Elija un municipio</option>
                                </select> 

                              </div>
                              <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i>  Buscar</button>
                        </form>
                        
                        @permission('olts-crear')
                        <div class="box-tools pull-right">
                            <div class="btn btn-default float-bottom btn-sm" data-toggle="modal" data-target="#formModal" data-tipo="agregar">
                                <i class="fa fa-plus"></i>  Agregar          
                            </div>
                        </div>
                        @endpermission
                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th scope="col">Nombre</th> 
                                    <th scope="col">IP</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                                @foreach($olts as $dato)
                                <tr>
                                    <td>{{$dato->id}}</td>
                                    <td>{{$dato->nombre}}</td>
                                    <td>{{$dato->ip}}</td>
                                    <td>{{$dato->municipio->NombreMunicipio}}</td>
                                    <td>{{$dato->estado}}</td>
                                    
                                    <td>
                                        @permission('olts-ver')
                                            <button class="btn btn-xs btn-success" data-toggle="modal" data-target="#formModal" data-tipo="show" data-id="{!! $dato->id !!}"><i class="fa fa-eye"></i></button>
                                        @endpermission

                                        @permission('olts-editar')
                                            <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#formModal" data-tipo="editar" data-id="{!! $dato->id !!}"><i class="fa fa-edit"></i></button>
                                        @endpermission
                                    </td>
                                    
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$olts->currentPage()}} de {{$olts->lastPage()}}. Total registros {{$olts->total()}}</span>
                        <!-- paginacion aquí -->                       
                         {!! $olts->appends(Request::only(['palabra','municipio']))->links() !!}
                    </div>
                </div>

                @permission('olts-crear')  
                    @include('adminlte::red.olts.partials.form')
                @endpermission
            </div>
        </div>
    </div>
    @section('mis_scripts')
    <script type="text/javascript">
        function buscarmunicipios(municipio,id){
    

            var parametros = {
                departamento_id : $('#departamento'+id).val(),
                '_token' : $('input:hidden[name=_token]').val()
            };

            $.post('/municipios/ajax', parametros).done(function(data){

                $('#municipio'+id).empty();
                $('#municipio'+id).append('<option value="">Elija un municipio</option>');
                $.each(data, function(index, municipiosObj){
                   if (municipio != null) {
                        if (municipiosObj.id == municipio) {

                            $('#municipio'+id).append('<option value="' + municipiosObj.id + '" selected>' + municipiosObj.nombre + '</option>');
                        }else{
                            $('#municipio'+id).append('<option value="' + municipiosObj.id + '">' + municipiosObj.nombre + '</option>');
                        }
                    }else{
                        $('#municipio'+id).append('<option value="' + municipiosObj.id + '">' + municipiosObj.nombre + '</option>');
                    }
                });
            });
        }
    </script>
    <script type="text/javascript">
        $("#departamento1").change(function() {            
            buscarmunicipios($(this).val(),1);
        });

        $("#departamento2").change(function() {            
            buscarmunicipios($(this).val(),2);
        });

        function limpiar(id){
            $("#departamento"+id).val('');
            $("select[name=estado]").val('');
            $('#municipio'+id).empty();
            $('#municipio'+id).append('<option value="">Elija un municipio</option>');
            $('input[name=password]').attr('required', true);
            $('#metodo').attr('name', '');
            $('#metodo').val('');
            $('input[name=nombre]').val('');
            $('input[name=ip]').val('');
            $('input[name=usuario]').val('');
            $('input[name=latitud]').val('');
            $('input[name=longitud]').val('');            
            
        }

        $('#formModal').on('show.bs.modal', function (event) {

            limpiar(2);
            var button = $(event.relatedTarget) // Button that triggered the modal
            var tipo = button.data('tipo')

            if (tipo == 'editar') {
                var id = button.data('id')
                $('#form').attr('action', '/red/olts/'+id);
                $('#metodo').attr('name', '_method');
                $('input[name=password]').attr('required', false);
                $('#metodo').val('PUT');
                traer_olt(id, true);

            }else{
                var id = button.data('id')
                traer_olt(id, false);
                $('#form').attr('action', '/red/olts/store');
                
            }

            var modal = $(this)
            modal.find('.modal-title').text(tipo)
        });        
    </script>

    @permission('olts-editar')
    <script>
        function traer_olt(id, is_edit){

            $.get('/red/olts/'+id+(is_edit ? '/edit' : '')).done(function(data){
                if (!jQuery.isEmptyObject(data)) {
                    $('input[name=nombre]').val(data.nombre).attr('disabled', !is_edit);
                    $('input[name=ip]').val(data.ip).attr('disabled', !is_edit);
                    $('input[name=usuario]').val(data.usuario).attr('disabled', !is_edit);
                    $('input[name=latitud]').val(data.latitud).attr('disabled', !is_edit);
                    $('input[name=longitud]').val(data.longitud).attr('disabled', !is_edit);
                    if (!is_edit) {
                        $('input[name=password]').val(data.password).attr('type', 'text');
                    }
                    $("#departamento2 option[value="+data.municipio['DeptId']+"]").attr("selected", true).attr('disabled', !is_edit);
                    $("select[name=version] option[value="+data.version+"]").attr("selected", true).attr('disabled', !is_edit);
                    $("select[name=estado] option[value="+data.estado+"]").attr("selected", true).attr('disabled', !is_edit);
                    buscarmunicipios(data.municipio_id,2);
                }
                

            }).fail(function(e){
              toastr.options.positionClass = 'toast-bottom-right';
              toastr.error(e.statusText);
          });
        }
    </script>
    @endpermission
    @endsection
@endsection