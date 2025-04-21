<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
	Route::get('clientes/{id}/edit', 'ClientesController@edit')->name('clientes.edit');
	Route::post('novedades/masivas', 'NovedadesController@agregar_masivas')->name('novedades.agregar_masivas');
	Route::post('users/guajira/create', 'ClientesController@createUsersGuajira')->name('users.guajira.create');
	#prueba
	Route::get('atencion-clientes/otro', 'AtencionClientesController@otro')->name('atencion-clientes.otro');


	Route::get('/storage/pqrs/{id}/{file}','PrivateController@archivos_pqrs');
	Route::get('/storage/campanas/{campana}/{cliente}/{file}','PrivateController@archivos_campanas');
	Route::get('/storage/proyectos/{id}/{file}','PrivateController@documentos_proyectos');
	Route::get('/storage/proyectos/{proyecto}/documental/{documental}/version/{version}/{file}','PrivateController@documental_proyectos');
	Route::get('/storage/contratos/{id}/{file}','PrivateController@archivos_contratos');
	Route::get('/storage/clientes/{id}/{file}','PrivateController@archivos_clientes');
	Route::get('/storage/installations/{id}/{file}','PrivateController@archivos_instalaciones');
	Route::get('/storage/installations/{id}/{file}','PrivateController@archivos_instalaciones');
	Route::get('/storage/red/plataforma/{id}/{file}','PrivateController@archivos_instrucciones')->name('gestion.view.instruccion');
	Route::get('/storage/mantenimientos/{tipo}/{id}/{file}','PrivateController@archivos_mantenimientos');


	Route::get('atencion-clientes/estadisticas', 'AtencionClientesController@estadisticas')->name('atencion-clientes.estadisticas');

	Route::get('mantenimientos/preventivos/{id}/cerrar', 'MantenimientoPreventivoController@cerrar_vista')->name('preventivos.cerrar_vista');
	Route::post('mantenimientos/preventivos/{id}/cerrar', 'MantenimientoPreventivoController@cerrar')->name('preventivos.cerrar');

	Route::get('mantenimientos/preventivos/{id}/acta', 'MantenimientoPreventivoController@generarActa')->name('preventivos.acta');

	Route::get('mantenimientos/correctivos/{id}/cerrar', 'MantenimientosController@cerrar_vista')->name('correctivos.cerrar_vista');
	Route::post('mantenimientos/correctivos/{id}/cerrar', 'MantenimientosController@cerrar')->name('correctivos.cerrar');

	Route::get('mantenimientos/correctivos/{id}/acta', 'MantenimientosController@generarActa')->name('correctivos.acta');


	Route::get('instalaciones/instalar', 'InstalacionesController@instalar')->name('instalaciones.instalar');
	//Route::group(['middleware' => ['role:admin,administrativo,comercial,agente-noc,contador,indicadores,agente-call-center,gerencia,asesor-punto-atencion,auditor']], function() {
		Route::resource('cambios-reemplazos', CambiosReemplazosController::class);
		Route::resource('recaudos', RecaudosController::class);
		Route::resource('atencion-clientes', AtencionClientesController::class);
		Route::resource('suspensiones-temporales', SuspensionTemporalController::class, ['parameters' => ['suspensiones-temporales' => 'suspension_temporal']]);

		Route::resource('motivos-atencion', MotivosAtencionController::class);
		Route::resource('puntos-atencion', PuntosAtencionController::class);
		Route::resource('novedades', NovedadesController::class);
		Route::resource('tickets', TicketsController::class);
		Route::resource('tickets.pruebas', TicketsPruebasController::class);
		Route::resource('encuestas-respuestas', RespuestasEncuestasClientesController::class);
		Route::resource('mantenimientos/preventivos', MantenimientoPreventivoController::class);
		Route::resource('mantenimientos/correctivos', MantenimientosController::class);
		Route::resource('mantenimientos.clientes', MantenimientosClientesController::class);
		Route::resource('mantenimientos.archivos', MantenimientosArchivosController::class);
		Route::resource('mantenimientos.equipos', MantenimientosEquiposController::class);
		Route::resource('mantenimientos.diagnosticos', MantenimientosDiagnosticosController::class);
		Route::resource('mantenimientos.direcciones', MantenimientosDireccionesController::class);
		Route::resource('mantenimientos.pruebas', MantenimientosPruebasController::class);
		Route::resource('mantenimientos.soluciones', MantenimientosSolucionesController::class);
		Route::resource('mantenimientos.fallas', MantenimientosFallasController::class);
		Route::resource('mantenimientos.paradas-reloj', MantenimientosParadaRelojController::class);
		Route::resource('mantenimientos.materiales', MantenimientosMaterialesController::class);
		Route::resource('encuestas', EncuestasController::class);
		Route::resource('puntos-atencion.areas', PuntosAtencionAreasController::class);
		Route::resource('puntos-atencion.ventanillas', PuntosAtencionVentanillasController::class);
		Route::resource('notas', FacturasNotasController::class);
		Route::resource('respuestas', CampanasRespuestasController::class);
		Route::resource('metas', MetasController::class);
		Route::resource('pqr', PQRController::class);
		Route::resource('pqr.archivos', PqrsArchivosController::class);
		Route::resource('planes', PlanesComercialesController::class);		

		Route::resource('solicitudes', SolicitudesController::class);
    	Route::resource('solicitudes.comentarios', SolicitudesComentariosController::class);
    	Route::resource('clientes-suspensiones', ClientesSuspensionesController::class);
		Route::resource('instalaciones', InstalacionesController::class);
		Route::resource('clientes/restricciones', ClienteRestriccionController::class);

		Route::resource('proveedores', ProveedorController::class, ['parameters' => ['proveedores' => 'proveedor']]);


		Route::resource('clientes', ClientesController::class);
		Route::resource('metas-clientes', MetasClientesController::class);
		Route::post('metas/ajax', 'MetasController@ajax')->name('metas.ajax');

		Route::resource('usuarios', UsuariosController::class);
  		Route::post('usuarios/ventas', 'UsuariosController@ventas')->name('usuarios.ventas');

		#Modulo gestion de red
		Route::resource('red/gestion', 'GestionRedController');

		#Modulo Documental de proyectos
		Route::resource('documental-proyectos', DocumentalProyectoController::class);
		Route::resource('documental-proyectos.mensuales', DocumentalMensualController::class);
		Route::resource('documental-proyectos.versiones', DocumentalVersionController::class);		
		Route::resource('documental-proyectos.versiones.archivos', DocumentalArchivoController::class);

	
		Route::resource('documental-carpetas', DocumentalCarpetaController::class);

		Route::resource('infraestructuras', InfraestructuraController::class);

		Route::resource('infraestructuras.propiedades', InfraestructurasPropiedadesContoller::class);

		Route::resource('infraestructuras.contactos', InfraestructurasContactosController::class);

		Route::resource('infraestructuras.proyectos', InfraestructurasProyectosController::class);

		Route::resource('infraestructuras.equipos', InfraestructurasEquiposController::class);

		Route::delete('infraestructuras/{infraestructura}/dependientes/{dependiente}', 'InfraestructuraController@desasociar_hijos')
			->name('infraestructuras.dependientes.destroy');

		Route::get('instalaciones/index/infraestructura', 'InstalacionesInfraestructuraController@index')->name('instalaciones.infra.index');

		Route::get('instalaciones/instalar/infraestructura', 'InstalacionesInfraestructuraController@instalar')->name('instalaciones.instalar.infra');

		Route::get('instalaciones/create/infraestructura/{infra_id}', 'InstalacionesInfraestructuraController@create')->name('instalaciones.create.infra');

		Route::post('instalaciones/store/infraestructura/{infra_id}', 'InstalacionesInfraestructuraController@store')->name('instalaciones.store.infra');

		Route::get('instalaciones/edit/infraestructura/{infra_id}', 'InstalacionesInfraestructuraController@edit')->name('instalaciones.edit.infra');
		
		Route::delete('instalaciones/destroy/infraestructura/{infra_id}', 'InstalacionesInfraestructuraController@destroy')->name('instalaciones.destroy.infra');
		
		Route::get('/instalacionesinfraestructura/sync', 'InstalacionesInfraestructuraController@syncData')->name('instalacionesinfraestructura.sync');

	//});

	#SUSPENSIONES-EXPORTAR
	Route::post('clientes-suspensiones/exportar', 'ClientesSuspensionesController@exportar')->name('clientes-suspensiones.exportar');
	#RESTRICCIONES-EXPORTAR
	Route::post('clientes/restricciones/exportar', 'ClienteRestriccionController@exportar')->name('restricciones.exportar');
	Route::post('encuestas-respuestas/exportar', 'RespuestasEncuestasClientesController@exportar');

	#SUSPENSIONES TEMPORALES AJAX
	Route::post('suspensiones-temporales/ajax', 'SuspensionTemporalController@ajax')->name('suspensiones-temporales.ajax');

	#CLIENTES SUSPENSIONES
	//Route::get('clientes/suspensiones', 'ClientesSuspensionesController@index')->name('clientes-suspensiones.index');

	#SOLICITUDES-EXPORTAR
  	Route::post('solicitudes/exportar', 'SolicitudesController@exportar')->name('solicitudes.exportar');

  	#PQR
  	Route::get('pqr/{id}/acta', 'PQRController@acta_traslado_generar')->name('pqrs.acta');
	Route::post('pqrs/exportar', 'PQRController@exportar')->name('pqrs.exportar');
	#CLASIFICACIONES PQRS AJAX
	Route::post('pqrs/clasificaciones/ajax', 'TipoPqrController@ajax')->name('pqrs-clasificaciones.ajax');




	#NOVEDADES-EXPORTAR
	Route::post('novedades/exportar', 'NovedadesController@exportar')->name('novedades.exportar');
	Route::post('novedades/cerrar', 'NovedadesController@cerrar')->name('novedades.cerrar');
	Route::get('novedades/{id}/ver','NovedadesController@ver');

	#NOTAS-EXPORTAR
	Route::post('notas/exportar', 'FacturasNotasController@exportar')->name('notas.exportar');

	#atencion-clientes-exportar
	Route::post('atencion-clientes/exportar', 'AtencionClientesController@exportar')->name('atencion-clientes.exportar');
	Route::get('atencion-clientes/{id}/atender', 'AtencionClientesController@atender')->name('atencion-clientes.atender');

	#Respuestas-Exportar
	Route::post('respuestas/exportar', 'RespuestasEncuestasClientesController@exportar')->name('respuestas.exportar');


	#
	Route::get('puntos-atencion/{id}/cliente', 'PuntosAtencionController@cliente')->name('puntos-atencion.cliente');

	#MOTIVOS ATENCION AJAX
	Route::post('motivos-atencion/ajax', 'MotivosAtencionController@ajax')->name('motivos-atencion.ajax');

	Route::post('recaudos/exportar', 'RecaudosController@exportar')->name('recaudos.exportar');

	#CARTERA
	Route::get('cartera', 'CarteraController@index')->name('cartera.index');
	Route::post('cartera/exportar', 'CarteraController@exportar')->name('cartera.exportar');

	#ESTADISTICA
	Route::get('estadisticas', 'EstadisticasController@facturacion')->name('estadisticas');
	Route::post('estadisticas/recaudos', 'EstadisticasController@recaudos')->name('estadisticas.recaudos');
	Route::post('estadisticas/exportar', 'EstadisticasController@exportar')->name('estadisticas.exportar');
	Route::post('estadisticas/suspendidos-recaudos', 'EstadisticasController@filtro_suspendidos_recaudos')->name('estadisticas.suspendidos-recaudos');


	#AJAX
	Route::post('cambios-reemplazos/ajax', 'CambiosReemplazosController@ajax')->name('cambios-reemplazos.ajax');
	#Exportar
	Route::post('cambios-reemplazos/exportar', 'CambiosReemplazosController@exportar')->name('cambios-reemplazos.exportar');


	#AJAX
	Route::post('facturas-notas/detalles', 'FacturasNotasController@detallesFeel')->name('facturas-notas.detalles');

	Route::post('facturas-notas/reportar', 'FacturasNotasController@reportar')->name('facturas-notas.reportar');

	#CAMPAÑA
	Route::get('campanas', 'CampanasController@index')->name('campanas.index');

	Route::get('campanas/create', 'CampanasController@create')
		->name('campanas.create'); 

	Route::post('campanas/create', 'CampanasController@store')
		->name('campanas.store');
	
	Route::get('campanas/{id}/edit', 'CampanasController@edit')
		->name('campanas.edit');

	Route::put('campanas/{id}/update', 'CampanasController@update')
		->name('campanas.update');
	
	Route::put('campanas/{id}/estado', 'CampanasController@estado')
		->name('campanas.estado');
		

	Route::get('campanas/{id}', 'CampanasController@show')
		->name('campanas.show');


	Route::get('campanas/{id}/estadisticas', 'CampanasController@estadisticas')
		->name('campanas.estadisticas');

	Route::post('campanas/exportar', 'CampanasController@exportar')
		->name('campanas.exportar');

	Route::get('campanas/{id}/llamar/{cliente}', 'CampanasRespuestasController@create')
		->name('campanas.llamar');	
	
	Route::post('campanas/{id}/responder/{cliente}', 'CampanasRespuestasController@store')
		->name('campanas.responder');
		
	Route::delete('campanas/{id}', 'CampanasController@destroy')->name('campanas.delete');


	Route::delete('campanas/{id}/campo/{campo}', 'CampanasController@destroyCampo')
	->name('campanas.campo.destroy');

	Route::post('opcion/ajax', 'CampanasCamposOpcionesController@nueva_opcion')->name('nueva_opcion.ajax');

	Route::post('campanas/ajax-consulta', 'CampanasController@ajax_consulta');


	Route::post('campanas/ajax_proyectos', 'CampanasController@ajax_proyectos')->name('campanas.ajax_proyectos');
	Route::post('campanas/ajax-municipios', 'CampanasController@ajax_municipios')->name('campanas.ajax_municipios');


	Route::put('campos/opciones/{opcion}', 'CampanasCamposOpcionesController@ajax_opciones')->name('campanas.ajax_campos_opciones');

	#Acuerdos
	Route::get('acuerdos', 'AcuerdosController@index')->name('acuerdos.index');

	Route::get('acuerdos/create', 'AcuerdosController@create')
		->name('acuerdos.create'); 

	Route::post('acuerdos/create', 'AcuerdosController@store')
		->name('acuerdos.store');
	
	Route::post('acuerdos/Cajax', 'AcuerdosController@crear_ajax')->name('acuerdos.crear_ajax');
	
	Route::get('acuerdos/{id}/edit', 'AcuerdosController@edit')
		->name('acuerdos.edit');

	Route::put('acuerdos/{id}/update', 'AcuerdosController@update')
		->name('acuerdos.update');

	Route::get('acuerdos/{id}', 'AcuerdosController@show')
		->name('acuerdos.show');

	Route::post('acuerdos/ajax', 'AcuerdosController@ajax')->name('acuerdos.ajax');

	Route::post('clientes/exportar', 'ClientesController@exportar')->name('clientes.exportar');

	#AUDITORIAS

	Route::resource('auditorias/clientes', AuditoriasController::class, ['names' => 'auditorias.clientes']);

	Route::put('clientes/{id}/subsanar', 'ClientesController@subsanar')
		->name('clientes.subsanar');

	Route::get('clientes/importar/datos', 'ClientesController@vistaImportar')
		->name('clientes.importar.datos')
		->middleware('role:admin,vendedor');

	Route::post('clientes/importar/datos', 'ClientesController@importar')
		->name('clientes.importar')
		->middleware('role:admin,vendedor');
	
	Route::get('instalaciones/create/{cliente}', 'InstalacionesController@create')->name('instalaciones.create');
	Route::get('instalaciones/importar/datos', 'InstalacionesController@vistaImportar')->name('instalaciones.importar.datos')->middleware('role:admin,tecnico');
	Route::post('instalaciones/importar/datos', 'InstalacionesController@importar')->name('instalaciones.importar')->middleware('role:admin,tecnico');

	Route::group(['middleware' => ['role:admin,agente-noc,auditor,tecnico']], function() {
		Route::resource('instalaciones.archivos', InstalacionesArchivosController::class);
	});

	Route::put('instalaciones/{id}/auditar', 'InstalacionesController@auditar')->name('instalaciones.auditar')->middleware('role:admin,auditor');

	#INSTALACION FORMATO PDF
	Route::get('instalaciones/{id}/pdf', 'InstalacionesController@pdf')->name('instalacion.pdf');

	#INSTALACION - Exportar
	Route::post('instalaciones/exportar', 'InstalacionesController@exportar')->name('instalaciones.exportar')->middleware('role:admin,administrativo,comercial,agente-noc,indicadores');


	#CLIENTES AJAX VALIDAR
	Route::post('clientes/ajaxvalidar', 'ClientesController@ajaxValidar')->name('clientes.ajaxvalidar')->middleware('role:admin,vendedor,comercial,agente-noc,indicadores');

	#CLIENTES AJAX
	Route::post('clientes/ajax', 'ClientesController@ajax')->name('clientes.ajax')->middleware('role:admin,agente-call-center,agente-noc,asesor-punto-atencion');

	#CLIENTES ARCHIVOS
	Route::put('archivosclientes/{id}', 'ArchivosClientesController@update')->name('archivosclientes.update');
	Route::post('archivosclientes/store', 'ArchivosClientesController@store')->name('archivosclientes.store');
	Route::delete('archivosclientes/{id}', 'ArchivosClientesController@destroy')->name('archivosclientes.delete');


	#AJAX Municipios
	Route::post('municipios/ajax', 'MunicipiosController@traer_municipios')->name('municipios.ajax');

	#AJAX Municipios
	Route::post('barrios/ajax', 'BarrioController@ajax')->name('barrios.ajax');


	#FACTURACION
	Route::get('facturacion', 'FacturacionController@index')->name('facturacion.index');
	Route::post('facturacion/create', 'FacturacionController@store')->name('facturacion.store')->middleware('role:admin,contador');
	Route::get('facturacion/create', 'FacturacionController@create')->name('facturacion.create')->middleware('role:admin,contador');
	Route::get('facturacion/{periodo}/{id}', 'FacturacionController@show')->name('facturacion.show');

	Route::get('facturacion/{periodo}', 'FacturacionController@view')->name('facturacion.view');

	Route::delete('facturacion/{id}', 'FacturacionController@destroy')->name('facturacion.destroy');

	Route::post('facturacion/exportar', 'FacturacionController@exportar')->name('facturacion.exportar');

	#AJAX
	Route::post('facturacion/ajax', 'FacturacionController@ajax')->name('facturacion.ajax')->middleware('role:admin,contador');

	#AJAX Reportar
	Route::post('facturacion/{periodo}', 'FacturacionController@reportar')->name('facturacion.reportar')->middleware('role:admin,contador');

	#ajax Facturacion Electronica
	Route::post('facturacion', 'FacturasElectronicasController@store')->name('facturacion_e.store')->middleware('role:admin,contador');

	#Detalles Facturacion Electronica
	Route::post('facturacion/{periodo}/detalles', 'DetallesFacturasElectronicasController@store')->name('facturacion_e.detalles.store')->middleware('role:admin,contador');

	#FACTURACION
	Route::get('facturacion/{id}/edit', 'FacturacionController@edit')->name('facturacion.edit')->middleware('role:admin');
	Route::put('facturacion/{id}', 'FacturacionController@update')->name('facturacion.update')->middleware('role:admin');

	#FACTURACION ELECTRONICA API
	Route::post('facturacion_electronica_api', 'FacturacionApiController@store')->name('facturacion_electronica_api.store');
	Route::put('facturacion_electronica_api/{id}', 'FacturacionApiController@update')->name('facturacion_electronica_api.update');
	Route::delete('facturacion_electronica_api/{id}', 'FacturacionApiController@destroy')->name('facturacion_electronica_api.destroy');


	Route::get('/home', 'HomeController@index')->name('home');

	#PERFIL
	Route::get('perfil/{id}', 'PerfilController@show')->name('perfil.show');
	Route::put('perfil/{id}', 'PerfilController@update')->name('perfil.update');


	#TICKETS
	Route::post('tickets/exportar', 'TicketsController@exportar')->name('tickets.exportar');

	#MANTNEIMIENTOS Exportar
	Route::post('mantenimientos/exportar', 'MantenimientosController@exportar')->name('mantenimientos.exportar');

	#PROYECTOS
	Route::resources([
        'proyectos' => 'ProyectosController',        
		'proyectos-municipios' => 'ProyectosMunicipiosController',
		'proyectos-costos' => 'ProyectosCostosController',
		'proyectos-clausulas' => 'ProyectosClausulasController',
		'proyectos.tipos-beneficiarios' => 'ProyectoTipoBeneficiarioController',
		'proyectos.documentacion' => 'ProyectoDocumentacionController',
		'proyectos.preguntas' => 'ProyectoPreguntaController',
    ]);

    Route::get('proyectos-municipios/{id}/edit/{meta_id}', 'ProyectosMunicipiosController@edit');


	Route::get('proyectos/{id}/estadisticas', 'ProyectosController@estadisticas')->name('proyectos.estadisticas');

	Route::put('proyectos/{id}', 'ProyectosController@updateService')->name('proyectos.updateservice')->middleware('role:admin');

	Route::post('proyectos/{id}/mapa', 'ProyectosController@mapa')->name('proyectos.mapa');

	Route::post('proyectos/ajax', 'ProyectosController@ajax')->name('proyectos.ajax');

	 Route::post('proyectos/{proyecto}/documentos/{id}/versionado', 'ProyectosDocumentosController@versionado')->name('proyectos.documentos.versionado');



	#RED OLTS
	Route::get('red/olts', 'OltsController@index')->name('red.olts.index');
	Route::get('red/olts/create', 'OltsController@create')->name('red.olts.create');
	Route::post('red/olts/store', 'OltsController@store')->name('red.olts.store');
	Route::get('red/olts/{id}', 'OltsController@show')->name('red.olts.show');
	Route::get('red/olts/{id}/edit', 'OltsController@edit')->name('red.olts.edit')->middleware('role:admin');
	Route::put('red/olts/{id}', 'OltsController@update')->name('red.olts.update')->middleware('role:admin');

	#PLAN COMERCIAL

	#AJAX
	Route::post('planes-comerciales/ajax', 'PlanesComercialesController@ajax')->name('panes-comerciales.ajax')->middleware('role:admin,vendedor,comercial,agente-noc');



	#APROVICIONAR
	Route::get('aprovisionar', 'AprovisionarController@index')->name('aprovisionar.index');
	Route::post('aprovisionar/exportar', 'AprovisionarController@exportar')->name('aprovisionar.exportar');

	Route::post('clientes/aprovisionar', 'AprovisionarController@store')->name('clientes.aprovisionar.store')->middleware('role:admin,agente-noc,indicadores');

	Route::delete('clientes/aprovisionar/{id}', 'AprovisionarController@destroy')->name('clientes.aprovisionar.destroy')->middleware('role:admin,agente-noc,indicadores');

	Route::post('clientes/aprovisionar/importar', 'AprovisionarController@importar')->name('clientes.aprovisionar.importar')->middleware('role:admin');

	#ESTUDIOS DEMANDA
	Route::get('estudios-demanda', 'EstudiosDemandaController@index')->name('estudios-demanda.index')->middleware('role:admin,administrativo,interventoria_lp15_centro');
	Route::post('estudios-demanda', 'EstudiosDemandaController@store')->name('estudios-demanda.store')->middleware('role:admin,administrativo');
	Route::get('estudios-demanda/{id}/edit', 'EstudiosDemandaController@edit')->name('estudios-demanda.edit')->middleware('role:admin,administrativo');
	Route::get('estudios-demanda/{id}', 'EstudiosDemandaController@show')->name('estudios-demanda.show')->middleware('role:admin,administrativo,interventoria_lp15_centro');
	Route::put('estudios-demanda/{id}', 'EstudiosDemandaController@update')->name('estudios-demanda.update')->middleware('role:admin,administrativo');
	Route::delete('estudios-demanda/{id}', 'EstudiosDemandaController@destroy')->name('estudios-demanda.delete')->middleware('role:admin,administrativo');

	Route::post('estudios-demanda/ajax-departamentos', 'EstudiosDemandaController@ajax_departamentos')->name('estudios-demanda.ajax_departamentos');

	Route::post('estudios-demanda/ajax-municipios', 'EstudiosDemandaController@ajax_municipios')->name('estudios-demanda.ajax_municipios');


	#ARCHIVOS ESTUDIOS DEMANDA
	Route::delete('archivos-estudios-demanda/{id}', 'ArchivosEstudiosDemandaController@destroy')->name('archivos-estudios-demanda.delete')->middleware('role:admin,administrativo');
	Route::post('archivos-estudios-demanda', 'ArchivosEstudiosDemandaController@store')->name('archivos-estudios-demanda.store')->middleware('role:admin,administrativo');


	#CONTRATOS
	//Route::resource('contratos','ClientesContratosController');
	Route::get('contratos', 'ClientesContratosController@index')->name('contratos.index');
	Route::get('clientes/{cliente}/contratos/create', 'ClientesContratosController@create')->name('clientes.contratos.create');
	Route::post('clientes/{cliente}/contratos/create', 'ClientesContratosController@store')->name('clientes.contratos.store');

	Route::get('clientes/{cliente}/contratos/{id}', 'ClientesContratosController@show')->name('clientes.contratos.show');
	Route::delete('clientes/{cliente}/contratos/{id}', 'ClientesContratosController@destroy')->name('clientes.contratos.destroy');
	Route::get('clientes/{cliente}/contratos/{id}/edit', 'ClientesContratosController@edit')->name('clientes.contratos.edit');
	Route::put('clientes/{cliente}/contratos/{id}/edit', 'ClientesContratosController@update')->name('clientes.contratos.update');





	#AJAX
	Route::post('contratos/ajax', 'ClientesContratosController@ajax')->name('contratos.ajax');

	#ENVIAR CONTRATO Y ACTAS
	Route::post('contratos/send', 'ClientesContratosController@sendContrato')->name('contratos.send');

	#CONTRATOS EXPORTAR
	Route::post('contratos/exportar', 'ClientesContratosController@exportar')->name('contratos.exportar');


	#CONTRATOS ARCHIVOS
	Route::resource('contratos-archivos','ContratosArchivosController');
	Route::post('contratos-archivos/ajax', 'ContratosArchivosController@ajax')->name('contratos-archivos.ajax');






	Route::post('contrato/servicio', 'ContratoServiciosController@store')->name('contrato.servicio.store')->middleware('role:admin,agente-noc,indicadores');

	Route::put('contrato/servicio/{id}', 'ContratoServiciosController@update')->name('contrato.servicio.update')->middleware('role:admin,agente-noc,indicadores');

	Route::delete('contrato/servicio/{id}', 'ContratoServiciosController@destroy')->name('contrato.servicio.delete');


	#INVENTARIOS - INSUMOS
	Route::get('inventarios/insumos', 'InsumosController@index')->name('inventarios.insumos.index');
	Route::get('inventarios/insumos/create', 'InsumosController@create')->name('inventarios.insumos.create')->middleware('role:admin');
	Route::get('inventarios/insumos/{id}', 'InsumosController@show')->name('inventarios.insumos.show')->middleware('role:admin,agente-noc');
	Route::get('inventarios/insumos/{id}/edit', 'InsumosController@edit')->name('inventarios.insumos.edit')->middleware('role:admin');
	Route::post('inventarios/insumos/create', 'InsumosController@store')->name('inventarios.insumos.store')->middleware('role:admin');
	Route::delete('inventarios/insumos/{id}', 'InsumosController@destroy')->name('inventarios.insumos.delete')->middleware('role:admin');

	#EXPORTAR INVENTARIOS
	Route::post('inventarios/exportar', 'InsumosController@exportar')->name('inventarios.insumos.exportar');
	


	#INVENTARIOS - ACTIVOS FIJOS

	Route::resource('inventarios/'.'insumos.activos-fijos', ActivosFijosController::class);


	/*Route::get('inventarios/activos-fijos/create', 'ActivosFijosController@create')->name('inventarios.activos-fijos.create')->middleware('role:admin');
	Route::get('inventarios/activos-fijos/{id}', 'ActivosFijosController@show')->name('inventarios.activos-fijos.show')->middleware('role:admin');
	Route::get('inventarios/activos-fijos/{id}/edit', 'ActivosFijosController@edit')->name('inventarios.activos-fijos.edit')->middleware('role:admin');
	Route::post('inventarios/activos-fijos/create', 'ActivosFijosController@store')->name('inventarios.activos-fijos.store')->middleware('role:admin');
	Route::delete('inventarios/activos-fijos/{id}', 'ActivosFijosController@destroy')->name('inventarios.activos-fijos.delete')->middleware('role:admin');*/

	#AJAX Inventario
	Route::post('inventarios/ajax', 'ActivosFijosController@traer_ont')->name('inventarios.ajax');
	Route::post('inventarios/validar/ajax', 'ActivosFijosController@validar_equipo_insumo')
		->name('inventarios.validar.ajax');


	#EXPORTAR
	Route::get('exportar/{usuario}', 'ExportarController@estado')->name('exportar.estado')->middleware('role:vendedor,comercial,admin');

	Route::post('exportar/efecty', 'ExportarController@efecty')->name('exportar.efecty')->middleware('role:admin,contador');

	Route::post('usuarios/exportar', 'UsuariosController@exportar')->name('usuarios.exportar');

	Route::post('roles/exportar', 'ExportarController@exportarRoles')->name('roles.exportar');

	Route::post('permisos/exportar', 'ExportarController@exportarPermisos')->name('permisos.exportar');



	#AJAX
	Route::post('proyectos-municipios/ajax', 'ProyectosMunicipiosController@ajax')->name('proyectos-municipios.ajax');

	#Buscar roles por su nombre
	Route::get('roles/buscar', 'UsuariosController@buscarRoles')->name('roles.buscar');
	#Buscar permisos por su nombre
	Route::get('permissions/buscar', 'UsuariosController@buscarPermisos')->name('permisos.buscar');

	#Reporte de las ONTS a la hora de suspender los clientes
	Route::get('red/reporte/onts', 'ReporteOntFallidoController@index')->name('red.reporte.onts');
	#Eliminar reporter ONT
	Route::delete('red/reporte/onts/{id}', 'ReporteOntFallidoController@destroy')->name('red.reporte.onts.destroy');

});
