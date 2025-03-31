<div class="modal fade" id="addContrato">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-blue">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Contrato</h4>
      </div>
      <form id="formContrato" action="{{route('contratos.store')}}" method="post">
        <input type="hidden" name="cliente_id" value="{{$cliente->ClienteId}}">
        {{csrf_field()}}
        <div class="modal-body">
          <br>
          <div class="row">
            <div class="col-md-12 table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Plan</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Tipo</th>
                    <th>Estrato</th>
                    <th style="width: 40px">Valor</th>
                  </tr>
                </thead>
                <tbody id="lista-planes">
                    @foreach($planes as $plan)
                      <tr>
                        <td>
                          <input type="radio" name="plan_internet" value="{{$plan->PlanId}}" required>
                        </td>
                        <td>{{$plan->nombre}}</td>
                        <td>{{$plan->DescripcionPlan}}</td>
                        <td>{{$plan->VelocidadInternet}} Megas</td>
                        <td>{{$plan->TipoDePlan}}</td>
                        <td>{{$plan->Estrato}}</td>
                        <td>
                          <span class="badge bg-default">${{number_format($plan->ValorDelServicio,0,',','.')}}</span>
                        </td>
                      </tr>
                    @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <div class="row" id="panel-detalles-contrato">
            <div class="form-group{{ $errors->has('vigencia') ? ' has-error' : '' }} col-md-4 col-sm-6 col-xs-6">
              <label>*Vigencia en meses</label>
              <input class="form-control" type="number" name="vigencia" id="vigencia" value="{{$cliente->proyecto->vigencia}}" placeholder="Vigencia en meses" required readonly>
            </div>

            <div class="form-group{{ $errors->has('tipo_cobro') ? ' has-error' : '' }} col-md-4 col-sm-6 col-xs-6">
              <label>*Tipo de Cobro</label>
              <input type="text" class="form-control" name="tipo_cobro" id="tipo_cobro" value="{{$cliente->proyecto->tipo_facturacion}}" required readonly>                                 
            </div>           

            

            <div class="form-group{{ $errors->has('fecha_contrato') ? ' has-error' : '' }} col-md-4 col-xs-12 col-sm-12">
              <label>*Fecha Contrato</label>
              <input type="date" name="fecha_contrato" class="form-control" value="" required>
            </div>

            <div class="form-group{{ $errors->has('fecha_instalacion') ? ' has-error' : '' }} col-md-4 col-xs-12 col-sm-12">
              <label>Fecha Instalación</label>
              <input type="date" name="fecha_instalacion" class="form-control" value="">
            </div>

            <div class="form-group{{ $errors->has('estado') ? ' has-error' : '' }} col-md-4 col-sm-6 col-xs-6">
              <label>*Estado</label>
              <select class="form-control" name="estado" required>
                <option value="">Elija un estado</option>
                <option value="PENDIENTE">PENDIENTE</option>
                <option value="VIGENTE">VIGENTE</option>
                <option value="SUSPENDIDO">SUSPENDIDO</option>
                <option value="FINALIZADO">FINALIZADO</option>
                <option value="ANULADO">ANULADO</option>
              </select>                   
            </div>

            <div class="form-group{{ $errors->has('clausula') ? ' has-error' : '' }} col-md-12 col-xs-12" col-sm-12>                   
              <div class="form-group">
                <div class="checkbox">
                  <h4 class="text-center">
                    Este contrato <b>{{($cliente->proyecto->clausula_permanencia)? 'SI TIENE' : 'NO TIENE'}}</b> clausula de permanencia.
                  </h4>              
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
          <button type="submit" id="btnSend" class="btn btn-primary">Agregar</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->