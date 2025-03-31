<div class="modal fade" id="addServicio">
		<div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header bg-blue">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		        <h4 class="modal-title"><i class="fa fa-plus"></i> Agregar Servicio</h4>
		      </div>
		    	<form action="{{route('contrato.servicio.store')}}" method="post">
			        <input type="hidden" name="contrato" value="{{$contrato->id}}">
			        <input type="hidden" name="cliente_id" value="{{$contrato->ClienteId}}">
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
		          	</div>
			        <div class="modal-footer">
			          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
			          <button type="submit" class="btn btn-primary">Agregar</button>
			        </div>
		      	</form>
		    </div>
		    <!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>