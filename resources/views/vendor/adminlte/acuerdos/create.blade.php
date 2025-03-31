@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-file-text-o"></i>  Crear Acuerdo</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">
        <div class="row">
            <div class="col-md-4">
                <div  class="panel panel-success"> 
                    <div class="panel-heading">
                        <h4 class="text-center"><i class="fa fa-search"></i> Buscar Cliente</h4>
                    </div>
                    <div class="panel-body"> 
                        <div class="form-group">
                            <input type="number" id="cedula" name="cedula" class="form-control" placeholder="Documento" value="{{old('cedula')}}" min="0" max="9999999999" autocomplete="off" required>
                        </div>
                    </div>
                </div>

                <div class="box box-primary" id="panel-cliente" style="display:none;">
                    <div class="box-header with-border bg-blue">
                        <h3 class="box-title"><i class="fa fa-user"></i> Datos Cliente</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-condensed">
                            <tbody>
                                <tr>
                                    <th>Identificacion</th>
                                    <td id="text-cedula"></td>
                                </tr>
                                <tr>
                                    <th>Nombre</th>
                                    <td id="text-nombre"></td>
                                </tr>
                                <tr>
                                    <th>Correo</th>
                                    <td id="text-correo"></td>
                                </tr>
                                <tr>
                                    <th>Direccion</th>
                                    <td id="text-direccion"></td>
                                </tr>
                                <tr>
                                    <th>Telefono</th>
                                    <td id="text-telefono"></td>
                                </tr>
                                <tr>
                                    <th>Proyecto</th>
                                    <td id="text-proyecto"></td>
                                </tr>
                                <tr>
                                    <th>Estado</th>
                                    <td id="text-estado"></td>
                                </tr>
                                <tr>
                                    <th>Tarifa Internet</th>
                                    <td id="text-tarifa-internet"></td>
                                </tr>
                                <tr>
                                    <th>Total Deuda</th>
                                    <td id="text-total-deuda"></td>
                                </tr>
                            </tbody>
                        </table>
                        <a id="link-cliente" class="btn btn-default btn-block" target="_blank"> <i class="fa fa-eye"></i> Ver Cliente</a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="box box-primary">

                    <div class="box-header with-border bg-blue">
                        <h3 class="box-title">Acuerdo De Pago</h3>
                    </div>
                
                    <div class="box-body">
                        
                        <form action="{{route('acuerdos.store')}}" method="POST">
                            {{csrf_field()}} 
                            <input type="text" hidden name="cliente_id" value="{{ old('cliente_id') }}" id="cliente_id">
                            <div  class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="text-center"><i class="fa fa-pencil-square-o"></i> Datos De Acuerdo</h4>
                                </div>
                                <div class="panel-body">                                      
                                    <div class="col-md-12">                                                   
                                        <div class="form-group {{ $errors->has('deuda') ? ' has-error' : '' }} col-md-4 ">
                                            <label>*Deuda Cliente</label>
                                            <input class="form-control" type="text" name="deuda" id="deuda" value="{{ old('deuda') }}" readonly>
                                        </div>
                                        <div class="form-group {{ $errors->has('cuotas') ? 'has-error' : '' }} col-md-4">
                                            <label >*Cuotas</label>
                                            <input type="number" class="form-control" name="cuotas" id="cuotas" value="{{ old('cuotas') }}" required>
                                        </div> 
                                        <div class="form-group {{ $errors->has('valor_inicial') ? ' has-error' : '' }} col-md-4 ">
                                            <label >Valor Inicial</label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                                <input type="text" class="form-control" name="valor_inicial" id="valor_inicial" value="{{ old('valor_inicial') }}" required>
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('valor_perdonar') ? ' has-error' : '' }} col-md-3">
                                            <label >Tipo de descuento</label>
                                            <select class="form-control"  name="perdonar_porcentual" id="perdonar_porcentual" required>
                                                <option value="">Seleccionar</option>
                                                <option value="porcentual" {{ (old('porcentual') == 'porcentual')? 'selected' : '' }} >Porcentual</option>
                                                <option value="monetario" {{ (old('monetario') == 'monetario')? 'selected' : '' }} >Monetario</option>
                                            </select>
                                        </div>
                                        <div class="form-group {{ $errors->has('valor_perdonar') ? ' has-error' : '' }} col-md-3">
                                            <label id="label_perdonarP" hidden>Porcentaje Perdonar</label>
                                            <label id="label_perdonarV">Valor Perdonar</label>
                                            <div class="input-group">
                                                <span id="signo_pesos" class="input-group-addon">$</span>
                                                <input class="form-control" type="text" name="valor_perdonar" id="valor_perdonar" value="{{ old('valor_perdonar') }}" required>
                                                <span id="signo_porcentaje"  class="input-group-addon hide">%</span>
                                            </div>
                                        </div>
                                        <div class="form-group {{ $errors->has('dia_pagar') ? ' has-error' : '' }} col-md-3 ">
                                            <label >*Fecha para pagar</label>
                                            <input class="form-control" type="date" name="dia_pagar" id="dia_pagar" value="{{ old('dia_pagar') }}" required>
                                        </div>                   
                                        <div class=" form-group btn-der text-right col-md-2" >
                                            <label >Generar</label><br>
                                            <button type="button"  id="generar_cuotas"  class=" btn btn-success" onclick="generar()"> <i id="load" class="fa fa-refresh"></i></button>							           
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Descontado</label>

                                            <div class="input-group">
                                                <span id="signo_pesos" class="input-group-addon">$</span>
                                                <input class="form-control" type="text" name="descontado" id="descontado" value="{{ old('descontado') }}" readonly>
                                            </div> 
                                        </div>
                                        <div class="form-group {{ $errors->has('descripcion') ? ' has-error' : '' }} col-md-12 ">
                                            <label >Descripcion</label>
                                            <textarea class="form-control" name="descripcion" id="descripcion" value="{{ old('descripcion') }}" ></textarea>
                                        </div>                 
                                    </div>
                                </div>
                            </div>

                            <div  class="panel panel-info">
                                <div class="panel-heading">
                                    <h4 class="text-center"><i class="fa fa-file-text-o"></i> Cuotas</h4>
                                </div>
                                <div class="panel-body"> 
                                    <div class="col-md-12">  
                            
                                        <table class="table table-striped" id="informcaion_registrar">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>*Fecha Pago</th>
                                                    <th>Valor Tarifa</th>
                                                    <th>Couta</th>
                                                    <th>Valor Pagar</th>
                                                </tr>
                                            </thead>
                                            <tbody id="t_cuotas">
                                                
                                            </tbody>
                                        </table>
                                        <br>
                                    </div>
                                </div>                                                 
                                <br>
                            </div>
                            <div class="btn-der text-right">
                                <button type="submit" disabled id="crear_acuerdo"  class="btn btn-primary"> <i id="load" class="fa fa-spinner"></i>Crear</button>							           
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> 
@endsection
@section('mis_scripts') 
    <script type="text/javascript" src="/js/acuerdos/funcion_moneda.js"></script>
    <script type="text/javascript" src="/js/acuerdos/cuotas.js"></script>
    <script type="text/javascript" src="/js/acuerdos/consultarCliente.js"></script>
    <script type="text/javascript" src="/js/acuerdos/validacion_porcentual.js"></script>
    <script type="text/javascript">
        $('#cedula').blur(function() {
            limpiar();
            consultar_cliente($(this).val());
        });

        $('#cuotas').on('input', function() {
            const cuotas = $(this).val();   
            if(cuotas == 1){
                var deuda = $('#deuda').val();
                $('#valor_inicial').val(deuda);
                $('#valor_inicial').attr('readonly', true);
            }else{
                $('#valor_inicial').val('');
                $('#valor_inicial').attr('readonly', false); 
            }
               
        // $('#resultado').text(valor);
        });
    </script>
@endsection

