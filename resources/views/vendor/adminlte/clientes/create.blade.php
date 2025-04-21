@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1> Crear Cliente </h1>
    
@endsection

@section('mis_styles')

<style>
	#jq-signature-canvas-1{
		/*background-image: url('/img/fondo_firma.jpg');*/
		background-size: contain;
		background-repeat: no-repeat;
	}
</style>
@endsection

@section('main-content')
<div class="container-fluid spark-screen">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<form id="form-cliente" action="{{route('clientes.store')}}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
					<div class="box-header with-border bg-blue">
						<h3 class="box-title">Crear Cliente</h3>
					</div>
					<div class="box-body">		          	
						{{csrf_field()}}

						<!--- Datos proyecto --->
						<div class="panel panel-info">
							<div class="panel-heading">
								<h4><i class="fa fa-tag"></i>  Proyecto</h4>
							</div>
							<div class="panel-body">
								<p>Elija el proyecto al que se asociará al cliente</p>

								<div class="row justify-content-center">									
									<div class="form-group{{ $errors->has('proyecto') ? ' has-error' : '' }} col-md-3 col-sm-6 text-center">
										<label>*Proyecto</label>
										<select class="form-control" name="proyecto" id="proyecto" required>
											<option value="">Elija una Opción</option>
											@foreach($proyectos as $proyecto)
											<option value="{{$proyecto->id}}">{{$proyecto->nombre}}</option>
											@endforeach
										</select>
									</div>
									<div class="form-group col-md-3 col-sm-6 text-center" id="comunidad-field" style="display: none;">
										<label>*Comunidad</label>
										<select class="form-control" name="ComunidadID" id="comunidad" class="ComunidadCampo" required data-unique="form-client">
											<option value="">Seleccione una comunidad</option>
											@foreach($comunidades as $comunidad)
											<option value="{{ $comunidad->ComunidadID }}">{{ $comunidad->nombre_comunidad }}</option>
											@endforeach
										</select>
									</div>
									<script> 
										document.addEventListener('DOMContentLoaded', function () { // Esperar a que el DOM esté listo
											document.getElementById('proyecto').addEventListener('change', function () {
												var comunidadField = document.getElementById('comunidad-field');
												var selectedValue = this.value; // Obtiene el ProyectoID seleccionado

												if (selectedValue == 14) { // Verifica si el ProyectoID es igual a 14
													comunidadField.style.display = 'block'; // Cambia directamente a display: block
													comunidadField.classList.add('show'); // Clase opcional para transiciones suaves
												} else {
													comunidadField.style.display = 'none'; // Oculta el campo "Comunidad"
													comunidadField.classList.remove('show'); // Limpia la clase opcional
													document.getElementById('comunidad').value = ''; // Resetea el campo "Comunidad"
												}
											});
										});
									</script>	
									<div class="form-group col-md-3 col-sm-6 text-center" id="nodo-field" style="display: none;">
										<label>*Nodo</label>
										<select class="form-control" name="nodo_id" id="nodo" class="NodoCampo" required>
											<option value="">Seleccione un tipo de Nodo</option>
													@foreach($nodos as $nodo)
													<option value="{{ $nodo->id }}">{{ $nodo->NombreNodo }}</option>
													@endforeach
										</select>
									</div>
									<script> 
										document.addEventListener('DOMContentLoaded', function () { // Esperar a que el DOM esté listo
											document.getElementById('proyecto').addEventListener('change', function () {
												var comunidadField = document.getElementById('nodo-field');
												var selectedValue = this.value; // Obtiene el ProyectoID seleccionado

												if (selectedValue == 14) { // Verifica si el ProyectoID es igual a 14
													comunidadField.style.display = 'block'; // Cambia directamente a display: block
													comunidadField.classList.add('show'); // Clase opcional para transiciones suaves
												} else {
													comunidadField.style.display = 'none'; // Oculta el campo "Comunidad"
													comunidadField.classList.remove('show'); // Limpia la clase opcional
													document.getElementById('comunidad').value = ''; // Resetea el campo "Comunidad"
												}
											});
										});
									</script>	
									<div class="form-group col-md-3 col-sm-6 text-center" id="servicio-field" style="display: none;">
										<label>*Servicio</label>
										<select class="form-control" name="tipo_servicio" id="servicio" class="ServicioCampo" required>
											<option value="">Seleccione un tipo de Servicio</option>
											<option value="COMUNIDAD DE CONECTIVIDAD">COMUNIDAD DE CONECTIVIDAD</option>
											<option value="PUNTO DE ACCESO">PUNTO DE ACCESO COMUNITARIO</option>
										</select>
									</div>
									<script>
										document.addEventListener('DOMContentLoaded', function () {
											// Elementos relevantes
											var proyectoDropdown = document.getElementById('proyecto'); // Dropdown de Proyecto
											var usuarioField = document.getElementById('servicio-field'); // Campo Usuario
										
											// Verificar el valor inicial al cargar la página
											if (proyectoDropdown.value === "14") {
												usuarioField.style.display = 'block'; // Mostrar el campo Usuario
											} else {
												usuarioField.style.display = 'none'; // Ocultar el campo Usuario
											}
										
											// Agregar un listener para cambios en el dropdown
											proyectoDropdown.addEventListener('change', function () {
												var selectedValue = this.value.trim(); // Obtener el valor seleccionado
										
												if (selectedValue === "14") {
													usuarioField.style.display = 'block'; // Mostrar el campo Usuario
												} else {
													usuarioField.style.display = 'none'; // Ocultar el campo Usuario
													document.getElementById('usuario').value = ''; // Limpiar el valor seleccionado en el dropdown Usuario
												}
											});
										});
										</script>	
										<div class="form-group col-md-3 col-sm-6 text-center" id="Tipo-Comunidad-field" style="display: none;">
											<label>*Tipo de Comunidad de Conectividad</label>
												<select class="form-control" name="tipo_comunidad" id="Comunidad" class="ComunidadCampo" required>
													<option value="">Seleccione un tipo de Servicio</option>
													<option value="HOGAR">HOGAR</option>
													<option value="ZONA WIFI">ZONA WIFI</option>
												</select>
										</div>		
										<script>
											document.addEventListener('DOMContentLoaded', function () { // Esperar a que el DOM esté listo
												document.getElementById('servicio').addEventListener('change', function () {
													var tipoComunidadField = document.getElementById('Tipo-Comunidad-field'); // Campo "Tipo de Comunidad de Conectividad"
													var selectedValue = this.value; // Obtiene la opción seleccionada en el campo "Servicio"
										
													if (selectedValue === 'COMUNIDAD DE CONECTIVIDAD') { // Si es "COMUNIDAD DE CONECTIVIDAD"
														tipoComunidadField.style.display = 'block'; // Muestra el campo "Tipo de Comunidad de Conectividad"
													} else {
														tipoComunidadField.style.display = 'none'; // Oculta el campo si no coincide
														document.getElementById('Comunidad').value = ''; // Resetea el valor del select de Comunidad
													}
												});
											});
										</script>										

									<div class="form-group{{ $errors->has('tipo_beneficiario') ? ' has-error' : '' }} col-md-3 col-sm-6 text-center" id="panel-tipo-beneficiario" style="display: none;">
										<label>Tipo de Beneficiario</label>
										<select class="form-control" name="tipo_beneficiario" id="tipo_beneficiario">
											<option>Elija una Opción</option>											
										</select>
									</div>

									<div class="form-group{{ $errors->has('departamento') ? ' has-error' : '' }} col-md-3 col-sm-6 text-center">
										<label>*Departamento: </label>
										<select class="form-control" name="departamento" id="departamento" required>
											<option value="">Elija una opción</option>
										</select>           			
									</div>

									<div class="form-group{{ $errors->has('municipio') ? ' has-error' : '' }} col-md-3 col-sm-6 text-center">
										<label>*Municipio: </label> 
										<select class="form-control" name="municipio" id="municipio" required>
											<option value="">Elija un Municipio</option>
										</select>
									</div>

									<div class="form-group{{ $errors->has('estrato') ? ' has-error' : '' }} col-md-3 col-sm-6 text-center">
					            		<label>*Estrato:</label>
					            		<select name="estrato" class="form-control" id="estrato">
	                                        <option value="">Elija una opción</option>
	                                        <option value="0">0</option>
	                                        <option value="1">1</option>
	                                        <option value="2">2</option>
	                                        <option value="3">3</option>
	                                        <option value="4">4</option>
	                                        <option value="5">5</option>
	                                        <option value="6">6</option>
	                                    </select>
	                                </div>
								</div>
							</div>
						</div>...........

						<!--- Planes y Tarifas --->
						<div class="panel panel-info" >
							<div class="panel-heading">
								<h4> <i class="fa fa-suitcase"></i>  Planes y Tarifas</h4>
							</div>
							<div class="panel-body">
								<p>De acuerdo al proyecto elegido, el contrato tendrá las siguientes caracteristicas.</p>
								<table class="table">
									<tr>
										<th>Vigencia: </th>
										<td id="vigencia"></td>
										<th>Tipo de Facturación: </th>
										<td id="tipo_facturacion"></td>
									</tr>
									<tr>
										<th>Dia corte facturación: </th>
										<td id="dia_corte"></td>
										<th>% intereses por mora: </th>
										<td id="porcentaje_mora"></td>
									</tr>
									<tr>
										<th>Condiciones del Plan: </th>
										<td colspan="3" ><p class="text-justify" id="condiciones_plan"></p></td>
									</tr>
									<tr>
										<th>Condiciones del Servicio: </th>
										<td colspan="3"><p class="text-justify" id="condiciones_servicio"></p></td>
									</tr>
									<tr>
										<th>Otros Costos: </th>
										<td colspan="3" id="otros_costos"></td>
									</tr>
									<tr>
										<th>Clausula de Permanencia: </th>
										<td colspan="3">
											<div id="clausulas">

											</div>
										</td>
									</tr>
								</table>

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
							                	<tr>
								                	<td colspan="7" class="text-center">
								                		SIN DATOS
								                	</td>								                	
								                </tr>
								            </tbody>
								        </table>
									</div>
								</div>
							</div>
						</div>

						<!--- Datos personales --->
						<div class="panel panel-info" id="datos-personales">
							<div class="panel-heading">
								<h4><i class="fa fa-user"></i>  Datos Personales</h4>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="form-group col-md-3 col-sm-6">
										<label>*Tipo de documento: </label>
										<select name="tipo_documento" id="tipo_documento" class="form-control" required>
											<option value="">Elija una opcion</option>
											<option value="C.C">Cédula de Ciudadanía</option>
											<option value="C.E">Cédula de Extranjería</option>
											<option value="P.P">Pasaporte</option>
											<option value="R.C">Registro Civil</option>
											<option value="T.I">Tarjeta de Identidad</option>
											<option value="NIT">Número de Identificación Tributaria</option>
										</select>
									</div>
									<div id="form-group-documento" class="form-group col-md-3 col-sm-6">
										<label>*Documento:</label>
										<input type="number" name="documento" class="form-control" placeholder="Documento"  min="0" max="9999999999" autocomplete="off" required>
									</div>
									<div class="form-group col-md-3 col-sm-6">
										<label>*Ciudad de Expedición:</label>
										<input type="text" name="lugar_expedicion" class="form-control" placeholder="Ciudad de Espedición" autocomplete="off" required>
									</div>
									<div class="form-group col-md-3 col-sm-6">
										<label>*Fecha de Nacimiento: </label>
	                                    <input type="date" class="form-control" name="fecha_nacimiento" placeholder="Fecha de nacimiento" required>
	                                </div>
								</div>
								<div class="row">
									<div class="form-group col-md-6 col-sm-6">
										<label>*Nombres:</label>
										<input type="text" name="nombres" class="form-control" placeholder="Nombres" autocomplete="off" required>
									</div> 

									<div class="form-group col-md-6 col-sm-6">
										<label>*Apellidos:</label>
										<input type="text" name="apellidos" class="form-control" placeholder="Apellidos" autocomplete="off" required>
									</div>

									<div class="form-group col-md-3 col-sm-12">
										<label>*Lugar de Nacimiento: </label>
	                                    <input type="text" class="form-control" name="lugar_nacimiento" placeholder="Lugar de nacimiento" autocomplete="off" required>
	                                </div>

									<div class="form-group col-md-3 col-sm-4">
	                                 	<label>*Género: </label>
	                                    <select name="genero" id="genero" class="form-control" required>
	                                    	<option value="">Elija una Opcion</option>
	                                        <option value="M">Masculino</option>
	                                        <option value="F">Femenino</option>
	                                        <option value="T">Transgénero</option>
	                                    </select>
	                                </div>

									<div class="form-group col-md-3 col-sm-4">
										<label>*Sexo:  </label>
										<select class="form-control" name="sexo" id="sexo" required>
											<option value="">Elija un opcion</option>
											<option value="Hembra">Hembra</option>
											<option value="Macho">Macho</option>
											<option value="Intersexual">Intersexual</option>
											<option value="Sin informacion">Sin información</option>
										</select>
									</div>

									<div class="form-group col-md-3 col-sm-4">
					            		<label>*Orientación Sexual:</label>
										<select name="orientacion_sexual" id="orientacion_sexual" class="form-control" required>
											<option value="">Elija un opcion</option>
											<option value="Heterosexual">Heterosexual</option>
											<option value="Homosexual">Homosexual</option>
											<option value="Bisexual">Bisexual</option>
											<option value="Sin informacion">Sin información</option>
										</select>
	                                </div>

	                                <div id="form-group-email" class="form-group col-md-6 col-sm-6">
										<label>*Correo:</label>
										<input type="email" name="correo" class="form-control" placeholder="Correo Electronico" onblur="validarEmail(this)" autocomplete="off">
									</div>                         
									
									<div class="form-group col-md-3 col-sm-6">
					            		<label>Teléfono:</label>
	                                     <input type="number" class="form-control" name="telefono" placeholder="Telefono" autocomplete="off"> 
					            	</div>

					            	<div class="form-group col-md-3 col-sm-6">
										<label>*Celular:</label>
										<input type="number" name="celular" class="form-control" placeholder="Celular"  autocomplete="off">
									</div>

									<div class="form-group col-md-3 col-sm-6">
										<label>*Étnia: </label>
										<select name="etnia" id="etnia" class="form-control" required>
											<option value="">Elija una opción</option>
											<option value="Mulato">Mulato</option>
											<option value="Indigena">Indigena</option>
											<option value="Negra">Negra</option>
											<option value="Afro">Afro</option>
											<option value="Palenquera">Palenquera</option>
											<option value="Raizal">Raizal</option>
											<option value="Gitanos - Rom">Gitanos - Rom</option>
											<option value="Mestiza">Mestiza</option>
											<option value="Sin Informacion">Sin Información</option>
										</select>
									</div>																
								
									

	                                <div class="form-group col-md-3 col-sm-6">
										<label>Nivel de Estudios</label>
										<select class="form-control" name="nivel_estudios" id="nivel_estudios" required>
											<option value="">Nivel de estudios</option>
											<option value="Preescolar">Preescolar</option>
											<option value="Basica">Básica</option>
											<option value="Media">Media</option>
											<option value="Superior pregrado">Superior pregrado</option>
											<option value="Superior posgrado">Superior posgrado</option>
											<option value="Sin informacion">Sin información</option>
										</select>
									</div>

									<div class="form-group col-md-3 col-sm-6">
					            		<label>*Discapacidad:</label>
										<select name="discapacidad" id="discapacidad" class="form-control" required>
											<option value="">Elija una opcion</option>
											<option value="Visual">Visual</option>
											<option value="Auditiva">Auditiva</option>
											<option value="Fisica">Fisica</option>
											<option value="Cognitiva-Intelectual">Cognitiva-Intelectual</option>
											<option value="Psicosocial">Psicosocial</option>
											<option value="Multiple">Multiple</option>
											<option value="Sin discapacidad">Sin discapacidad</option>
										</select>
	                                </div>
								</div>							
							</div>
						</div>						

						<!--- Direccion --->
						<div class="panel panel-info" id="panel-direccion">
							<div class="panel-heading">
								<h4> <i class="fa fa-map-marker"></i>  Lugar de Recidencia</h4>
							</div>
							<div class="panel-body">

								<div class="row">
									<div class="col-md-6">
										<label>Dirección: </label>
										<div class="row" >										
											<!-- Grupo 1-->
											<div class="form-group col-md-5 col-sm-12 col-xs-12" >
												<select id="d1" class="form-control" >
													<option value=""></option>
													<option value="AUTOP">Autopista</option>
													<option value="AV">Avenida</option>
													<option value="CL">Calle</option>
													<option value="KR">Carrera</option>
													<option value="CT">Carretera</option>
													<option value="DG">Diagonal</option>
													<option value="KM">Kilómetro</option>
													<option value="TV">Transversal</option>
												</select>
											</div>

											<!-- Grupo 2-->
											<div class="form-group col-md-7 col-sm-12 col-xs-12"  >
												<div class="input-group">
													<input type="text" id="d2" class="form-control" autocomplete="off">
													<span class="input-group-addon" style="border: 0px;">#</span>
												<input type="text" id="d3" class="form-control" autocomplete="off">
													<span class="input-group-addon" style="border: 0px;">-</span>
													<input type="text" id="d4" class="form-control"  autocomplete="off">
												</div>
											</div>

											<!-- Grupo 3-->
											<div class="form-group col-md-5 col-sm-7 col-xs-7" >
												<select id="d5" class="form-control">
													<option value=""></option>
													<option value="BL">Bloque</option>
													<option value="CLJ">Callejón</option>
													<option value="CN">Camino</option>
													<option value="CAS">Caserio</option>
													<option value="ED">Edificio</option>
													<option value="ET">Etapa</option>
													<option value="FCA">Finca</option>
													<option value="HC">Hacienda</option>
													<option value="LT">Lote</option>
													<option value="PRJ">Paraje</option>
													<option value="PA">Parcela</option>
													<option value="PD">Predio</option>
													<option value="SEC">Sector</option>
													<option value="TZ">Terraza</option>
													<option value="TO">Torre</option>
													<option value="VRD">Vereda</option>
												</select>
											</div>
											<div class="form-group col-md-7 col-sm-5 col-xs-5" >
												<input type="text" id="d6" class="form-control" autocomplete="off">
											</div>

											<!-- Grupo 4-->
											<div class="form-group col-md-5 col-sm-9 col-xs-9" >
												<select id="d7" class="form-control">
													<option value=""></option>
													<option value="APTO">Apartamento</option>
													<option value="CA">Casa</option>
													<option value="CS">Consultorio</option>
													<option value="LC">Local</option>
													<option value="OF">Oficina</option>
													<option value="PI">Piso</option>
												</select>
											</div>

											<div class="form-group col-md-3 col-sm-3 col-xs-3" >
												<input type="text" id="d8" class="form-control" autocomplete="off">
											</div>

											<!-- Grupo 5-->
											<div class="form-group col-md-5 col-sm-9 col-xs-9" >
												<select id="d9" class="form-control">
													<option value=""></option>
													<option value="MZ">Manzana</option>
													<option value="SMZ">Super Manzana</option>
													<option value="CONJ">Conjunto</option>
													<option value="IN">Interior</option>
												</select>
											</div>

											<div class="form-group col-md-3 col-sm-3 col-xs-3" >
												<input type="text" id="d10" class="form-control" autocomplete="off">
											</div>

										</div>
									</div>
									<div class="col-md-6">
										<div class="row">
											<div class="form-group col-md-12 col-sm-6">
												<label>*Direccion Real:</label>
												<input type="text" class="form-control" name="direccion" placeholder="Direccion" autocomplete="off" readonly>
											</div>

											<div class="form-group col-md-12 col-sm-6">
												<label>*Direccion Recibo:</label>
												<input type="text" class="form-control" name="direccion_recibo" placeholder="Direccion Recibo"  autocomplete="off">
											</div>

											<div class="form-group col-md-6 col-sm-6">
												<label>Barrio:</label>
												<input type="barrio" class="form-control" name="barrio" placeholder="Barrio" autocomplete="off">
											</div>

											<div class="form-group col-md-6 col-sm-6">
												<label>Urbanización:</label>
												<input type="urbanizacion" class="form-control" name="urbanizacion" placeholder="Urbanización" autocomplete="off">
											</div>

											<div class="form-group col-md-6 col-sm-6">
												<label>*Zona:</label>
												<select name="zona" id="zona" class="form-control" required>
													<option value="">Elija una opción</option>
													@foreach($zonas as $zona)
														<option value="{{$zona}}">{{$zona}}</option>
													@endforeach
												</select>
											</div>

											<div class="form-group col-md-6 col-sm-6">
												<label>Localidad:</label>
												<select name="localidad" id="localidad" class="form-control" required>
													<option value="">Elija una opción</option>
													@foreach($localidades as $localidad)
														<option value="{{$localidad}}">{{$localidad}}</option>
													@endforeach
												</select>
											</div>

											<div class="form-group col-xs-12 col-md-6 col-sm-6">
												<label>Coordenadas</label>
												<div class="input-group input-group">
													<input type="text" name="coordenadas" id="coordenadas" placeholder="Latitud,Longitud" class="form-control" required autocomplete="off">
													<span class="input-group-btn">
														<button class="btn btn-info btn-flat" type="button" onclick="getUserPosition()"><i class="fa fa-map-marker"></i> Obtener</button>
													</span>
												</div>
												<span class="help-block"></span>
											</div>
											

											<div class="form-group col-md-6 col-sm-6">
												<label>*Tipo de Vivienda:</label>
												<select name="tipo_vivienda" id="tipo_vivienda" class="form-control" required>
													<option value="">Elija una opción</option>
													<option value="Arrendada">Arrendada</option>
													<option value="Familiar">Familiar</option>
													<option value="Propia">Propia</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!--- Fotos --->
						<div class="panel panel-info" id="panel-fotos">
							<div class="panel-heading">
								<h4> <i class="fa fa-folder-open-o"></i>  Documentación Requerida</h4>
							</div>
							<div class="panel-body">
								<div class="row" id="documentacion_proyecto">

								</div>
								<div class="row">
									<div class="form-group col-md-6 col-sm-6">
										<label>*Firma Cliente</label>
										<select name="pregunta_firma" class="form-control" required>
											<option value="">Elija una opción</option>
											<option>FIRMAR</option>
											<option>SUBIR FIRMA</option>
										</select>        
										<span class="help-block"></span>
									</div>

									<div id="firmaSubir" class="form-group col-md-6 col-sm-6" style="display:none;">
										<label>*Firma Cliente</label>
										<input type="file" class="form-control" name="firma" value="" accept="image/png, image/gif, image/jpeg,  image/jpg">
										<span class="help-block"></span>
									</div>

									@if(empty(Auth::user()->firma))
										<div class="form-group col-md-6 col-sm-6">
											<label>*Firma Vendedor</label>
											<select name="pregunta_firma_usuario" class="form-control" required>
												<option value="">Elija una opción</option>
												<option>FIRMAR</option>
												<option>SUBIR FIRMA</option>
											</select>        
											<span class="help-block"></span>
										</div>

										<div id="firmaUsuarioSubir" class="form-group col-md-6 col-sm-6" style="display:none;">
											<label>*Firma Vendedor</label>
											<input type="file" class="form-control" name="firma_usuario" value="" accept="image/png, image/gif, image/jpeg,  image/jpg">
											<span class="help-block"></span>
										</div>

									@endif
								</div>
							</div>
						</div>

						<!--- Preguntas --->
						<div class="panel panel-info" id="panel-preguntas" style="display:none;">
							<div class="panel-heading">
								<h4> <i class="fa fa-question-circle"></i>  Preguntas</h4>
							</div>
							<div class="panel-body">
								<div class="row" id="preguntas_container">

								</div>
							</div>
						</div>


					</div>
					<!-- /.box-body -->
					<div class="box-footer clearfix">
						<button type="submit" id="enviar" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i>  Guardar</button>
					</div>
				</form>
				<div id="result"></div>
			</div>
		</div>
	</div>
</div>

	@include('adminlte::instalaciones.partials.firma.add')


	@section('mis_scripts')

		<script src="/js/signature_pad.umd.js"></script>
		<script src="/js/coordenadas.js"></script>
		<script src="/js/firma.js"></script>
		<script src="/js/usuarios/firma.js"></script>
		<script>
			var resultado_cedula, resultado_email;
			var firma = null;
			var firma_usuario = null;
		</script>

		<script src="/js/clientes/firma.js"></script>

		<script type="text/javascript">

			toastr.options.positionClass = 'toast-bottom-right';

			const formatoMoneda = (valor) => {
				return valor.toLocaleString('es-CO',{style:'currency', currency:'COP', minimumFractionDigits:0});
			}

			$('#proyecto').on('change', function(){				

				if($(this).val().length > 0) {

					$('#tipo_beneficiario').empty();
					$('#departamento').empty();
					$('#municipio').empty();
					$('#clausulas').empty();
					$('#otros_costos').empty();
					$('#lista-planes').empty();
					$('#documentacion_proyecto').empty();

					var parameters = {
						proyecto_id : $(this).val(),
						'_token' : $('input:hidden[name=_token]').val()
					};

					$.post('/proyectos/ajax', parameters).done(function(data){						
						$('#vigencia').text(data.proyecto['vigencia'] + ' Meses');
						$('#tipo_facturacion').text(data.proyecto['tipo_facturacion']);
						$('#dia_corte').text('Día ' + data.proyecto['dia_corte_facturacion'] + ' de cada mes.');
						$('#porcentaje_mora').text(data.proyecto['porcentaje_interes_mora']);
						$('#condiciones_plan').text((data.proyecto['condiciones_plan'] != null)? data.proyecto['condiciones_plan'] : 'SIN DEFINIR');
						$('#condiciones_servicio').text((data.proyecto['condiciones_servicio'] != null)? data.proyecto['condiciones_servicio'] : 'SIN DEFINIR');

						if(data.proyecto['clausula'].length > 0){

							let clausulas_list = '';

							var k = 1;

							$.each(data.proyecto['clausula'], function(index, clausulaObj){ 
								if(k == 1){
									clausulas_list += '<tr>';
								}

								clausulas_list += `<td class="text-center"> <b>MES ${clausulaObj.numero_mes}</b> </br> ${formatoMoneda(parseFloat(clausulaObj.valor))}</td>`;

								if(k == 6){
									clausulas_list += '</tr>';
									 k = 0;
								}

								k += 1;
							});

							$('#clausulas').append(`
							<table class="table table-bordered">
								<thead>
									<tr>
										<th class="text-center" colspan="6">Valor a pagar si termina el contrato anticipadamente según el mes</th>
									</tr>                 
								</thead>
								<tbody>									
									${clausulas_list}
								</tbody>
    						</table>
							`);
						}else{							
							$('#clausulas').text('SIN DEFINIR');
						}

						if(data.proyecto['costo'].length > 0){

							let costos_list = '';

							$.each(data.proyecto['costo'], function(index, costoObj){

								costos_list += `
									<tr>
										<td>${costoObj.concepto}</td>
										<td>${(costoObj.descripcion != null) ? costoObj.descripcion : ''}</td>
										<td>${formatoMoneda(parseFloat(costoObj.valor))} ${(costoObj.iva == 'SI')? ' más IVA': ''}</td>
									</tr>								
								`;
								
							});

							$('#otros_costos').append(`
							<table class="table">
								<thead>
									<tr>
										<th>Concepto</th>
										<th>Descripcion</th>
										<th>Valor</th>             
									</tr>                   
								</thead>
								<tbody>
									${costos_list}
								</tbody>
							</table>
							`);



						}else{							
							$('#otros_costos').text('SIN DEFINIR');
						}

						if(data.documentacion.length > 0){

							$.each(data.documentacion, function(index, documentacionObj){

								$('#documentacion_proyecto').append(`
									<div class="form-group col-md-6 col-sm-6">
										<label>${(documentacionObj.tipo == 'OBLIGATORIO')? '*' : ''} ${documentacionObj.alias}</label>
										<input type="file" name="archivos[${documentacionObj.nombre}]" class="form-control" ${(documentacionObj.tipo == 'OBLIGATORIO')? 'required' : ''}>
									</div>
								`);
								
							});

						}else{							
							$('#documentacion_proyecto').text('SIN DEFINIR');
						}

						if(data.preguntas.length > 0){
							$('#preguntas_container').empty();
							$('#panel-preguntas').show();

							$.each(data.preguntas, function(index, pregunta){

								let input = "";

								switch (pregunta.tipo) {
									case 'textarea':
										input = `<textarea name="respuesta[${pregunta.id}]" class="form-control" ${(pregunta.obligatoriedad == '1')? 'required' : ''}></textarea>`;										
										break;

									case 'select':
										var opciones = `<option value="" disabled selected>Elija una opción</option>`;
										var opciones_respuesta = JSON.parse(pregunta.opciones_respuesta);

										$.each(opciones_respuesta, function(index, opcion_respuesta){
											opciones += `<option value="${opcion_respuesta}">${opcion_respuesta}</option>`;
										});

										input = `<select name="respuesta[${pregunta.id}]" class="form-control" ${(pregunta.obligatoriedad == '1')? 'required' : ''}>${opciones}</select>`;										
										break;

									case 'check':

										var opciones_respuesta = JSON.parse(pregunta.opciones_respuesta);

										$.each(opciones_respuesta, function(index, opcion_respuesta){
											input += `<div class="checkbox">
															<label>
																<input type="checkbox" name="respuesta[${pregunta.id}][]" value="${opcion_respuesta}">
																${opcion_respuesta}
															</label>
														</div>`;
										});

										break;
								
									default:
										input = `<input type="${pregunta.tipo}" name="respuesta[${pregunta.id}]" class="form-control" ${(pregunta.obligatoriedad == '1')? 'required' : ''}>`;
										break;
								}								

								$('#preguntas_container').append(`
									<div class="form-group col-md-6 col-sm-6">
										<label>${(pregunta.obligatoriedad == '1')? '*' : ''} ${pregunta.pregunta}</label>
										${input}
									</div>
								`);

							});


						}else{
							$('#panel-preguntas').hide();
							$('#preguntas_container').empty();
						}


						$('#departamento').append('<option value="">Elija un departamento</option>');
						$.each(data.departamentos, function(index, departamentosObj){                   
							$('#departamento').append('<option value="'+departamentosObj.id+'">'+departamentosObj.nombre+'</option>');                    
						});

						if(data.tipos_beneficiarios.length > 0){

							$('#tipo_beneficiario').attr('required', true);
							$('#tipo_beneficiario').parent().show(2000);

							$('#tipo_beneficiario').append('<option value="">Elija un tipo de beneficiario</option>');
							$.each(data.tipos_beneficiarios, function(index, tipoBeneficiarioObj){                   
								$('#tipo_beneficiario').append('<option value="'+tipoBeneficiarioObj.nombre+'">'+tipoBeneficiarioObj.nombre+'</option>');                    
							});
						}else{
							$('#tipo_beneficiario').attr('required', false);
							$('#tipo_beneficiario').parent().fadeOut(2000);
						}

					}).fail(function(e){
						toastr.error(e.message);
					});					
				}
			});
			
			$('#departamento').on('change', function(){

				if($(this).val().length > 0){				
					var parameters = {
						departamento_id : $(this).val(),
						proyecto_id : $('#proyecto').val(),
						'_token' : $('input:hidden[name=_token]').val()
					};

					$.post('/proyectos-municipios/ajax', parameters).done(function(data){

						$('#municipio').empty();
						$('#municipio').append('<option value="">Elija un municipio</option>');
						$.each(data, function(index, municipiosObj){
							$('#municipio').append('<option value="'+municipiosObj.municipio_id+'" data-proyecto-municipio="'+municipiosObj.id+'">'+municipiosObj.nombre+'</option>');                    
						});
					}).fail(function(e){
						toastr.error(e.message);
					});
				}
			});

			$("#tipo_beneficiario").on('change', function(){

				if($(this).val().length > 0){
					
					if($(this).val() == 'Estrato 1'){
						traer_planes(1);
						$('#estrato option:eq(2)').prop('selected', true);

					}else if($(this).val() == 'Estrato 2'){
						traer_planes(2);
						$('#estrato option:eq(3)').prop('selected', true);
					}
				}
			});

			$('#estrato').on('change', function(){
				if($(this).val().length > 0){
					traer_planes($(this).val());
				}
			});

			$('#municipio').on('change', function(){
				if($(this).val().length > 0){
					traer_planes($('#estrato').val());
				}
			});

			const traer_planes = (estrato) => {

			    const parameters = {
					'estrato' : estrato,
					'proyecto' : $('#proyecto').val(),
					'municipio' : $('#municipio').val(),
					'_token' : $('input:hidden[name=_token]').val()
				}

				$.post("/planes-comerciales/ajax", parameters, function(data){

					$('#lista-planes').empty();

					if($.isEmptyObject(data)){
						$('#lista-planes').append('<tr><td colspan="7" class="text-center">No se encontraron datos.</td></tr>');
					}else{					
			       
				        $.each(data, function(index, planesObj){
				        	$('#lista-planes').append(`
							<tr>
								<td>
									<input type="radio" name="plan_internet" value="${planesObj.PlanId}" required>
								</td>
								<td>${planesObj.nombre}</td>
								<td>${planesObj.DescripcionPlan}</td>
								<td>${planesObj.VelocidadInternet} Megas</td>
								<td>${planesObj.TipoDePlan}</td>
								<td>${planesObj.Estrato}</td>
								<td>
									<span class="badge bg-default">${formatoMoneda(parseFloat(planesObj.ValorDelServicio))}</span>
								</td>
							</tr>
							`);
				        });
				    }
				});
			}
			

			$('input[name=documento]').blur(function(){
				validarCedula();
			});			

			function validarCedula(){

				if ($('input[name=documento]').val().length > 0) {
					var parametros = {
						'cedula' : $('input[name=documento]').val(),
						'validar' : 'Identificacion',
						'_token' : $('input:hidden[name=_token]').val()
					}

					$.post('/clientes/ajaxvalidar', parametros).done(function(data){
						if (data > 0) {
							toastr.options.positionClass = 'toast-bottom-right';
	  						toastr.error("El documento ya existe");
	  						$('#form-group-documento').removeClass('has-success').addClass('has-error');
	  						$('input[name=documento]').focus().select();
	  						resultado_cedula =  false;
						}else{
							toastr.options.positionClass = 'toast-bottom-right';
	  						toastr.success("Documento Validado.");
	  						$('#form-group-documento').removeClass('has-error').addClass('has-success');
	  						resultado_cedula = true;
						}
					});
				}

				return resultado_cedula;
			}

			const validarEmail = (correo) => {
				if (correo.value.length > 0) {
					var parametros = {
						'cedula' : correo.value,
						'validar' : 'CorreoElectronico',
						'_token' : $('input:hidden[name=_token]').val()
					}

					$.post('/clientes/ajaxvalidar', parametros).done(function(data){
						if (data > 0) {
							toastr.options.positionClass = 'toast-bottom-right';
	  						toastr.error("El Correo ya existe");
	  						$('#form-group-email').removeClass('has-success').addClass('has-error');
	  						correo.focus().select();
	  						resultado_email = false;
						}else{
							toastr.options.positionClass = 'toast-bottom-right';
	  						toastr.success("Correo Validado.");
	  						$('#form-group-email').removeClass('has-error').addClass('has-success');
	  						resultado_email = true;
						}
					});
				}

				return resultado_email;
			}

			
		</script>

		<script type="text/javascript">
		    $('#d1,#d2,#d3,#d4,#d5,#d6,#d7,#d8,#d9,#d10,input[name=barrio],input[name=urbanizacion]').focusout(function() {
		      	var numero = '';
		      	if ($('#d3').val().length > 0) {
		      		numero = ' # ' + $('#d3').val() + ' - ' + $('#d4').val();
		      	}

		      	$('input[name=direccion]').val($('#d1').val() + ' ' + $('#d2').val() + numero + ' ' + $('#d5').val() + ' ' + $('#d6').val()  + ' ' + $('#d7').val()  + ' ' + $('#d8').val()  + ' ' + $('#d9').val()  + ' ' + $('#d10').val());
		    });
		</script>
		
		

		<script type="text/javascript">

			$("#form-cliente").submit(function(e) {
			    e.preventDefault();
			    var f = $(this);
				var formData = new FormData(this);
			    
			    if ($('select[name="pregunta_firma"]').val() == 'FIRMAR') {
					formData.append('firma', firma);
				}

				if ($('select[name="pregunta_firma_usuario"]').val() == 'FIRMAR') {
					formData.append('firma_usuario', firma_usuario);
				}else if ($('select[name="pregunta_firma_usuario"]').val() == 'SUBIR FIRMA'){
					formData.append('firma_usuario', $('input[name="firma_usuario"]')[0].files[0]);
				}
			   

			    if (resultado_cedula && resultado_email) {

					$('#result').removeClass('overlay').empty();			
					$('#result').addClass('overlay').append('<i class="fa fa-refresh fa-spin"></i>');
		    
			    	
					$.ajax({
						url: "/clientes",
						type: "post",
						dataType: "json",
						data: formData,
						cache: false,
						contentType: false,
						processData: false
					})
					.done(function(res){

						console.log(res);

						if(res['tipo_mensaje'] == 'success'){                        
							toastr.options.positionClass = 'toast-bottom-right';
							toastr.success(res['mensaje']);

							setTimeout(() => {
								location.replace("/clientes/create");
							}, "3000");

						}else{

							$('#result').removeClass('overlay').empty();

							toastr.options.positionClass = 'toast-bottom-right';
							toastr.error(res['mensaje']); 
						}

					}).fail(function( jqXHR, textStatus, errorThrown ) {
						$('#result').removeClass('overlay').empty();

						console.log(jqXHR)
						console.log(textStatus)
						console.log(errorThrown)

						if(jqXHR.status == 422){

							var objeto = JSON.parse(jqXHR.responseText);

							$.each(objeto, function(index, respuestaObj){			                   
								var padre = $('[name="' + index+'"]').parent();
								padre.removeClass('has-success').addClass('has-error');
								padre.find('.help-block').text(respuestaObj)
								//padre.append('<span class="text-danger">' + respuestaObj +'</span>');
							});

							toastr.options.positionClass = 'toast-bottom-right';
							toastr.error("Corrija los campos");
						}else{
							toastr.options.positionClass = 'toast-bottom-right';
							toastr.error(errorThrown);
						}                      
					});
			    }
			});
			@if ($errors->any())
				<div class="alert alert-danger">
					<ul>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</ul>
				</div>
			@endif
		</script>
    @endsection
@endsection