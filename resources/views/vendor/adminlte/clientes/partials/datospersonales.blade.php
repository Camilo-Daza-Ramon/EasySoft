<div class="col-md-12">
  <div class="panel panel-default"> 
    <div class="panel-heading">
      <h2>
        <i class="fa fa-user"></i>  Datos Personales        

        <div class="panel-tools pull-right">
          @permission('clientes-actualizar')
            
              <a href="{{route('clientes.edit', $cliente->ClienteId)}}" class="btn btn-info btn-sm"> <i class="fa fa-edit"></i> <span class="hidden-xs">Editar</span></a>
          @endpermission
        </div>
      </h2>
      
    </div>
    <div class="panel-body table-responsive">
    	<div class="row">
    		<div class="col-md-6">
				<div class="row">
					<div class="col-md-4">
						<label>Tipo de Documento</label>
						<p>{{$cliente->TipoDeDocumento}}</p>			
					</div>
					<div class="col-md-4">
						<label>Documento</label>
						<p>{{$cliente->Identificacion}}</p>			
					</div>
					<div class="col-md-4">
						<label>Expedida en</label>
						<p>{{$cliente->ExpedidaEn}}</p>			
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label>Proyecto</label>
						<p>{{$cliente->proyecto->NumeroDeProyecto}}</p>			
					</div>
					<div class="col-md-4">
						<label>Tipo Beneficiario</label>
						<p>{{$cliente->tipo_beneficiario}}</p>			
					</div>
					<div class="col-md-4">
						<label>Clasificación</label> <br>
						<span class="label bg-black">{{$cliente->Clasificacion}}</span>
					</div>
					
					
				</div>
				<div class="row">
					<div class="col-md-6">
						<label>Teléfono</label>
						<p>{{$cliente->TelefonoDeContactoFijo}}</p>			
					</div>
					<div class="col-md-6">
						<label>Celular</label>
						<p>{{$cliente->TelefonoDeContactoMovil}}</p>			
					</div>
									
				</div>

				<div class="row">
					<div class="col-md-6">
						<label>Genero</label>
						<p>{{$cliente->genero}}</p>			
					</div>
					<div class="col-md-6">
						<label>Correo</label>
						<p>{{$cliente->CorreoElectronico}}</p>			
					</div>	
				</div>

				<div class="row">
					<div class="col-md-6">
						<label>Dirección Real</label>
						<p>
							{{$cliente->DireccionDeCorrespondencia}}<br>
						</p>			
					</div>
					<div class="col-md-6">
						<label>Dirección Recibo</label>
						<p>
							{{$cliente->direccion_recibo}} <br>
						</p>			
					</div>

					<div class="col-md-6">
						<label>Municipio</label>
						<p>
							{{$cliente->municipio->NombreMunicipio}}<br>
						</p>			
					</div>

					<div class="col-md-6">
						<label>Departamento</label>
						<p>
							{{$cliente->municipio->departamento->NombreDelDepartamento}}<br>
						</p>			
					</div>
					<div class="col-md-6">
						<label>Barrio</label>
						<p>{{$cliente->Barrio}}</p>
					</div>
					<div class="col-md-6">
						<label>Urbanizacion</label>
						<p>{{$cliente->NombreEdificio_o_Conjunto}}</p>
					</div>
				</div>

				<div class="row">
					<div class="col-md-4">
						<label>Estrato</label>
						<p>{{$cliente->Estrato}}</p>			
					</div>

					<div class="col-md-4">
						<label>Estado Cliente</label>
						<p>{{$cliente->Status}}</p>
					</div>

					<div class="col-md-4">
						<label>Vendedor</label>
						<p>
							@if(!empty($cliente->vendedor->name))
							{{$cliente->vendedor->name}}
							@else								
								SIN ESPECIFICAR
							@endif
						</p>

						
					</div>

					<div class="col-md-4">
						<label>Auditor</label>
						<p>
							@if(!empty($cliente->auditor->name))
							{{$cliente->auditor->name}}
							@else								
								SIN ESPECIFICAR
							@endif
						</p>						
					</div>

					<div class="col-md-4">
						<label>Fecha de Creación</label>
						<p></p>
					</div>
					<div class="col-md-4">
						<label>Última Actualización</label>
						<p></p>						
					</div>
				</div>
			</div>
			<div class="col-md-6 no-padding">
				<div id="map" style="width: 100%; height: 400px; margin: 15px 0px 15px 0px;"></div>
			</div>
    	</div>
    </div>    
  </div>
</div>

@if(isset($cliente->meta_cliente) || isset($cliente->reemplazo))
<div class="col-md-12">
  <div class="panel panel-default"> 
    <div class="panel-heading">
      <h2><i class="fa fa-thumb-tack"></i> Información Meta</h2> 
    </div>
    <div class="panel-body table-responsive">
      <div class="row">
        <div class="col-md-4">
          <label>Meta Asignado:</label>
          <p>{{(isset($cliente->meta_cliente)) ? $cliente->meta_cliente->meta->nombre : $cliente->reemplazo->meta_cliente->meta->nombre}}</p>
        </div>
        <div class="col-md-4">
          <label>ID-PUNTO:</label>
          <p>{{(isset($cliente->meta_cliente)) ? $cliente->meta_cliente->idpunto : $cliente->reemplazo->meta_cliente->idpunto}}</p>
        </div>
        <div class="col-md-4">
        	<label>ESTADO:</label>
        	@if(isset($cliente->reemplazo))
        		<p>Entró en reemplazo de <a href="{{route('cambios-reemplazos.show', $cliente->reemplazo->id)}}">{{$cliente->reemplazo->meta_cliente->cliente->Identificacion}}</a></p>

        		@if(isset($cliente->meta_cliente->reemplazo))
			  		<br>Se reemplazó por 
			  		<a href="{{route('cambios-reemplazos.show',$cliente->meta_cliente->reemplazo->id)}}">{{$cliente->meta_cliente->reemplazo->cliente->Identificacion}}
			  		</a>
			  	@endif
        	@else
	        	<p>@if($cliente->reporte == 'GENERADO')
			        @if($cliente->ProyectoId == 7)
			          Reportado a DIALNET
			        @else
			          Reportado a INTERVENTORIA.
			        @endif
			      @else
			      	SIN REPORTAR.
			      @endif
			  	
			  	@if(isset($cliente->meta_cliente->reemplazo))
			  		<br>Se reemplazó por 
			  		<a href="{{route('cambios-reemplazos.show',$cliente->meta_cliente->reemplazo->id)}}">{{$cliente->meta_cliente->reemplazo->cliente->Identificacion}}
			  		</a>
			  	@endif

			  	</p>
			@endif
        </div>
      </div>
    </div>
  </div>
</div>
@endif