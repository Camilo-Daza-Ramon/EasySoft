@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-building-o"></i>  Proveedores</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header bg-blue with-border">
                        <form action="{{route('proveedores.index')}}" role="search" method="GET">
                            
                                <div class="btn-group pull-right">

                                    <button type="button" id="opciones" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">                                    
                                        <span id="icon-opciones" class="fa fa-gears"></span>
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    
                                    <ul class="dropdown-menu" role="menu">
                                    
                                        @permission('proveedores-crear')
                                            <li><a href="#" data-toggle="modal" data-target="#proveedorAdd"><i class="fa fa-plus"></i> Agregar</a></li>
                                        @endpermission 

                                    </ul>
                                    
                                </div>
                            

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">

                                        <div class="form-group col-md-4">
                                            <input type="number" class="form-control" name="identificacion" placeholder="Número identificacion" value="{{(isset($_GET['identificacion'])? $_GET['identificacion']:'')}}" autocomplete="off">
                                        </div>
                                        @auth
                                            @if(!in_array(auth()->user()->id, [274, 275]))
                                                <div class="form-group col-md-4">
                                                    <select class="form-control" name="proyecto" id="proyecto">
                                                        <option value="">Elija un proyecto</option>
                                                        @foreach($proyectos as $proyecto)
                                                            <option value="{{$proyecto->ProyectoID}}" {{(isset($_GET['proyecto'])) ? (($_GET['proyecto'] == $proyecto->ProyectoID) ? 'selected' : '') : ''}}>{{$proyecto->NumeroDeProyecto}}</option>
                                                        @endforeach
                                                    </select>
                                                </div> 
                                            @endif
                                        @endauth


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
                                                <option value="">Elija un municipio</option>
                                            </select> 
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
                                    <button type="submit" class="btn btn-default" style="height: 85px;">
                                        <i class="fa fa-search"></i>  Buscar
                                    </button>
                                </div>
                            </div>
                        </form>                        


                    </div>
                    <div class="box-body table-responsive">
                        <table  id="areas" class="table table-bordered table-striped dataTable">
                            <tbody>
                                <tr>
                                    <th scope="col">Identificación</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Municipio</th>
                                    <th scope="col">Departamento</th>                                    
                                    <th scope="col">Estado</th>
                                    <th>Acciones</th>
                                </tr>
                                @if($proveedores->count() > 0)
                                    @foreach($proveedores as $proveedor)
                                    <tr>
                                        <th>
                                            @permission('proveedores-ver')
                                                <a href="#">{{$proveedor->identificacion}}</a>
                                            @else
                                                {{$proveedor->identificacion}}
                                            @endpermission
                                        </th>

                                        <td>{{mb_convert_case($proveedor->nombre, MB_CASE_TITLE, "UTF-8")}}</td>
                                        <td>{{$proveedor->tipo}}</td>
                                        <td>{{$proveedor->municipio->NombreMunicipio}}</td>
                                        <td>{{$proveedor->municipio->departamento->NombreDelDepartamento}}</td>
                                        <td>{{$proveedor->estado}}</td>

                                        <td>
                                            @permission('proveedores-ver')
                                                <button class="btn btn-xs btn-success" data-toggle="modal" data-target="#proveedorShow" data-id="{{$proveedor->id}}"><i class="fa fa-eye"></i></button>
                                            @endpermission

                                            @permission('proveedores-editar')
                                                <a href="#" data-id="{{$proveedor->id}}" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#proveedorEdit"> <i class="fa fa-edit"></i></a>
                                            @endpermission    
                                            
                                            @permission('proveedores-eliminar')
                                                <form action="{{route('proveedores.destroy', $proveedor->id)}}" method="post" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="delete"> 
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    <button type="submit" onclick="return confirm('Estas seguro Eliminar a este proveedor?');" title="Eliminar" class="btn btn-danger btn-xs">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                </form>
                                            @endpermission
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">NO HAY REGISTROS</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <span class="dataTables_info" id="example2_info" role="status" aria-live="polite">Página {{$proveedores->currentPage()}} de {{$proveedores->lastPage()}}. Total registros {{$proveedores->total()}}</span>
                        <!-- paginacion aquí -->
                        {!! $proveedores->appends(Request::only(['identificacion','proyecto', 'departamento', 'municipio', 'estado']))->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @permission('proveedores-crear')
        @include('adminlte::proveedores.create')
    @endpermission

    @permission('proveedores-editar')
        @include('adminlte::proveedores.edit')
    @endpermission

    @permission('proveedores-ver')
        @include('adminlte::proveedores.show')
    @endpermission



    @section('mis_scripts')
        <script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>
        <script type="text/javascript" src="/js/myfunctions/buscarmunicipios2.js"></script>
        <script type="text/javascript" src="/js/myfunctions/exportar_ajax.js"></script>

        @permission('proveedores-exportar')
        <script type="text/javascript">
            $('#exportar').on('click',function(){
                var parametros = {
                    identificacion : "{!! (isset($_GET['identificacion'])? $_GET['identificacion']:'') !!}",
                    proyecto : "{!! (isset($_GET['proyecto'])? $_GET['proyecto']:'') !!}",
                    municipio : "{!! (isset($_GET['municipio'])? $_GET['municipio']:'') !!}",
                    departamento : "{!! (isset($_GET['departamento'])? $_GET['departamento']:'') !!}",
                    estado : "{!! (isset($_GET['estado'])? $_GET['estado']:'') !!}",
                    '_token' : $('input:hidden[name=_token]').val() 
                }

                exportarConAjax('/proveedores/exportar', parametros);

            });

            
            $('#form-proveedores-editar select[name=departamento]').on('change', function(){ 
                if($(this).val().length > 0){     
                    buscarmunicipios(null, 
                        $('#form-proveedores-editar select[name=departamento]'),$('#form-proveedores-editar #municipio_select'));
                }
            });

            $('#form-proveedores-crear #departamento_select').on('change', function(){ 
                if($(this).val().length > 0){     
                    buscarmunicipios(null, $('#departamento_select'), $('#municipio_select'));
                }
            });

            $('.modal#proveedorEdit').on('show.bs.modal', function (event) {

                var button = $(event.relatedTarget) 
                var id = button.data('id')
                $('#form-proveedores-editar').attr('action', '/proveedores/'+id);
                traer_proveedor(id, '#form-proveedores-editar');

                var modal = $(this)
                modal.find('.modal-title').text('Editar Proveedor');
            });  

            $('.modal#proveedorShow').on('show.bs.modal', function (event) {

                var button = $(event.relatedTarget) 
                var id = button.data('id')
                $('#form-proveedores-ver').attr('action', '/proveedores/'+id);
                traer_proveedor(id, '#form-proveedores-ver');

                var modal = $(this)
                modal.find('.modal-title').text('Proveedor');
            });  


            function traer_proveedor(id, form_id){
                $.get('/proveedores/'+id+'/edit').done(function(data){
                    if (!jQuery.isEmptyObject(data)) {
                        const proveedor = data.proveedor;
                        const is_show =  form_id == '#form-proveedores-ver';                  
                        const form = $(form_id);

                        form.find('input[name=nombre]').val(proveedor.nombre).attr('disabled', is_show);
                        form.find('input[name=correo_electronico]').val(proveedor.correo_electronico).attr('disabled', is_show);
                        form.find('select[name=tipo]').val(proveedor.tipo).attr('disabled', is_show);
                        form.find('select[name=tipo_identificacion]').val(proveedor.tipo_identificacion).attr('disabled', is_show);
                        form.find('input[name=identificacion]').val(proveedor.identificacion).attr('disabled', is_show);
                        form.find('input[name=telefono]').val(proveedor.telefono).attr('disabled', is_show);
                        form.find('input[name=celular]').val(proveedor.celular).attr('disabled', is_show);
                        form.find('select[name=departamento]').val(proveedor.municipio.DeptId).attr('disabled', is_show);
                        form.find('input[name=direccion]').val(proveedor.direccion).attr('disabled', is_show);
                        
                        buscarmunicipios(proveedor.municipio_id, 
                            form.find('select[name=departamento]'), form.find('#municipio_select'));
                        
                        form.find('#municipio_select').val(proveedor.direccion).attr('disabled', is_show);                        
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