@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-file-text-o"></i>  Editar {{$campana->nombre}}</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" id="panel-generar">
                                
                    <div class="box-body">
                        
                        <form id="actualizarCampaña" action="{{route('campanas.update',$campana->id)}}" method="POST">
                            <input type="hidden" name="_method" value="put">
                            {{csrf_field()}}   
                            
                            <div  class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="text-center"><i class="fa fa-pencil-square-o"></i> Datos De Campaña</h4>
                                </div>
                                <div class="panel-body">                                   
                                    <div class="col-md-8">                                                             
                                        <label>*Nombre</label>
                                        <input type="text" name="nombre_campana" value="{{$campana->nombre}}" placeholder="Nombre Campaña" class="form-control" required>                                                                                        
                                    </div>
                                    <div class="form-group col-md-4">
                                            <div class="checkbox mt-3">
                                                <label>
                                                    <input type="checkbox" name="restricciones" value="1" {{($campana->sin_restricciones)? 'checked' : ''}}>
                                                    <span class="ml-1"> Sin restricciones ni Solicitudes</span>
                                                </label>
                                            </div>
                                        </div>                                                              
                                </div>
                            </div>
                                                                                   
                            <div  class="panel panel-info" >
                                <div class="panel-heading">
                                    <h4 class="text-center"><i class="fa fa-file-text-o"></i> Información a mostrar</h4>
                                </div>
                                <div class="panel-body" >                        
                                    <div class="col-md-12 ">
                                        <div class="lista-nombres ">
                                            <ul id="campos_visualizar">
                                                @foreach ($campana->campos_visualizar as $visualizar )
                                                    <li>
                                                        <input class="material-icons" type="checkbox" name="campos[]" checked="checked" value="{{$visualizar->campo}}">
                                                        <label >{{$visualizar->campo}}</label>
                                                    </li>                                       
                                                @endforeach
                                                @if (count($campos_faltantes) > 0)
                                                    @foreach ($campos_faltantes as $campo_f )
                                                        <li>
                                                            <input class="material-icons" type="checkbox" name="campos[]"  value="{{$campo_f}}">
                                                            <label >{{$campo_f}}</label>
                                                        </li>                                       
                                                    @endforeach
                                                @endif
                                            </ul>
                                          </div>                                       
                                    </div>
                                </div>
                            </div>

                            <div  class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="text-center"><i class="fa fa-file-text-o"></i> Información a registrar</h4>
                                </div>
                                
                                <div class="panel-body"> 
                                    <div class="col-md-12">  
            
                                        <table class="table table-responsive table-striped" id="informcaion_registrar">
                                            <thead>
                                                <tr>
                                                    <th>*Nombre</th>
                                                    <th>*Tipo</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($campana->campos as $campo)
                                                    @if ($campo->nombre != 'Motivo_atencion' && $campo->nombre != 'Categoria_atencion')
                                                        <tr>
                                                            <td>
                                                                <b>{{$campo->nombre}}</b>
                                                            </td>
                                                            <td>
                                                                @if ($campo->tipo == 'SELECCION_CON_MULTIPLE_RESPUESTA' or $campo->tipo == 'SELECCION_CON_UNICA_RESPUESTA')
                                                                    <div class="dropdown" id="{{ $campo->id }}" >
                                                                        <div class="row">
                                                                            <div class="col-md-8 text-muted">
                                                                                <b>{{ str_replace('_', ' ', $campo->tipo) }}</b>                                                                      
                                                                            </div>
                                                                            <div class="col-md-4">
                                                                                <button type="button" onclick="agregar_opcionEdit(this)" class="btn btn-info btn-xs"><i class="fa fa-plus"></i>
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        @foreach ($campo->opciones as $key => $opcion )
                                                                            <div class="row">
                                                                                <div class="col-md-8">
                                                                                    <li class="list-option">{{ $opcion->valor }}</li>
                                                                                </div>
                                                                                <div class="col-md-4">
                                                                                    @if ($opcion->estado == 1)
                                                                                        <input type="checkbox" name="editar_opcion" onclick="editarOpcion({!! $opcion->id !!})"   value="1" checked="checked">
                                                                                    @else
                                                                                        <input type="checkbox" name="editar_opcion" onclick="editarOpcion({!! $opcion->id !!})"  value="1" >
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                        
                                                                    </div>
                                                                @else
                                                                    {{ $campo->tipo }}
                                                                @endif
                                                            </td>
                                                            <td>

                                                                @if($campo->nombre != 'Estado')
                                                                    @if ($campo->estado == 1)
                                                                        <input type="checkbox" name="{{$campo->nombre}}" value="1" checked="checked">
                                                                    @else
                                                                        <input type="checkbox" name="{{$campo->nombre}}" value="1" >
                                                                    @endif

                                                                    @if (count($campo->respuestas) == 0)
                                                                        <button name="elimanarCampo" type="button" class="btn btn-danger btn-xs" data-campana="{{$campana->id}}" data-campo="{{$campo->id}}" title="Eliminar">
                                                                            <i class="fa fa-trash-o"></i> 
                                                                        </button>
                                                                    @endif
                                                                @endif
                                                            </td> 
                                                        </tr>
                                                    @endif
                                                    
                                                @endforeach
                                                                                                                                         
                                            </tbody>                                                                                                                
                                        </table> 
                                        
                                        <div class="btn-der text-center">
                                            <button id="adicional"  type="button" class="btn btn-success btn-sm"> Agregar Más... </button>
                                        </div>
                                    </div>
                                </div>

                                <br>
                            </div>
                                                    
                            <br>
                            <div class="btn-der text-right">
                                <input type="submit" id="actualizar_campana"  class="btn btn-primary " value="Actualizar"/>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection   
@section('mis_scripts')
    <script>
        const tipos_campos = {!! json_encode($tipos_campos) !!};
    </script>
 
    <script type="text/javascript" src="/js/campaña/campana_create.js"></script>
    <script>

        $(document).ready(function(){

            $("button[name=elimanarCampo]").on('click',function(){

                var campaña = $(this).attr('data-campana');
                var campo = $(this).attr('data-campo');
                var url = '/campanas/'+campaña+'/campo/'+campo;
                
                $.ajax({
                    url: url ,
                    method: 'DELETE',
                    data:{
                        _token: '{{ csrf_token() }}'
                    },
                    
                })
                .done(function(res){
                    const boton = document.querySelector('button[data-campo="'+campo+'"]');
                    const tr = boton.parentNode.parentNode;
                    tr.remove();
                    toastr.options.positionClass = 'toast-bottom-right';
                    toastr.success("Campo eliminado correctamente.");
			           
                })
                .fail( function(xhr, textStatus, errorThrown ) {
                    
                    toastr.options.positionClass = 'toast-bottom-right';
                    toastr.error("El campo se pudo Eliminar");			            
                });
            });
                      
        });
       
               
      </script>

@endsection