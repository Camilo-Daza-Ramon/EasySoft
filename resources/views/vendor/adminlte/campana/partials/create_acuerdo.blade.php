<div class="modal fade" id="createAcuerdo">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> 
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-check-square-o"></i> Acuerdo</h4>
      </div>      
      <div class="modal-body">
        <div class="row">
            <div class="box-body">
                
                <form id="crear_acuerdo_ajax">
                    <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
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
                                <div class="form-group {{ $errors->has('valor_perdonar') ? ' has-error' : '' }} col-md-3 ">
                                    <input type="text" name="perdonar_porcentual" id="perdonar_porcentual" value="{{ $campaña->tipo_descuento }}" hidden>
                                    <input type="checkbox" id="perdonar_valor" hidden>
                                    <label id="label_perdonarP" hidden>Porcentaje Perdonar</label>
                                    <label id="label_perdonarV">Valor Perdonar</label>
                                    <div class="input-group">
                                        <span id="signo_pesos" class="input-group-addon">$</span>
                                        <input class="form-control" type="text" name="valor_perdonar" id="valor_perdonar" value="{{ old('valor_perdonar') }}" readonly>
                                        <span id="signo_porcentaje"  class="input-group-addon hide">%</span>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Descontado</label> 
                                    <div class="input-group">
                                        <span id="signo_pesos" class="input-group-addon">$</span> 
                                        <input type="text" name="descontado" id="descontado" value="" readonly="readonly" class="form-control">
                                    </div>
                                </div> 
                                <div class="form-group {{ $errors->has('dia_pagar') ? ' has-error' : '' }} col-md-3 ">
                                    <label >*Fecha para pagar</label>
                                    <input class="form-control" type="date" name="dia_pagar" id="dia_pagar" value="{{ old('dia_pagar') }}" required>
                                </div>                   
                                <div class=" form-group btn-der text-right col-md-2">
                                    <label >Generar</label><br>
                                    <button type="button"  id="generar_cuotas"  class=" btn btn-success" onclick="generar()"> <i id="load" class="fa fa-refresh"></i></button>							           
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
                        <button  disabled id="crear_acuerdo"  class="btn btn-primary"> <i id="load" class="fa fa-spinner"></i>Crear</button>							           
                    </div>
                </form>
            </div>
        </div>
      </div> 
     
    </div>
  </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal --> 

