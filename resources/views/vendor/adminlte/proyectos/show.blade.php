@extends('adminlte::layouts.app')

@section('contentheader_title')
<h1><i class="fa fa-cubes"></i> Proyecto - {{$proyecto->NumeroDeProyecto}}</h1>
@endsection

@section('mis_styles')
<link rel="stylesheet" type="text/css" href="https://adminlte.io/themes/AdminLTE/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
@endsection

@section('other-notifications')
@if(count($alertas) > 0)
<div class="alert alert-warning">
	<h5><i class="icon fa fa-warning"></i> Importante!</h5>
	<ul>
		@foreach($alertas as $alerta)
		<li>{!!$alerta!!}</li>
		@endforeach
	</ul>
</div>
@endif
@endsection

@section('main-content')
<div class="container-fluid spark-screen">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-solid">
				<div class="box-body">
					<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 no-padding">
						<!-- required for floating -->
						<!-- Nav tabs -->
						<ul class="nav nav-tabs tabs-left">
							<li class="active">
								<a href="#detalles" data-toggle="tab">
									<i class="fa fa-list"> </i> <span class="hidden-xs hidden-sm hidden-md">Detalles</span>
								</a>
							</li>
							@permission('facturacion-electronica-api-ver')
							<li>
								<a href="#api" data-toggle="tab">
									<i class="fa fa-cloud"></i> <span class="hidden-xs hidden-sm hidden-md">Api de Facturación</span>
								</a>
							</li>
							@endpermission
							@permission('proyectos-tipos-beneficiarios-listar')
							<li>
								<a href="#tipos_beneficiarios" data-toggle="tab">
									<i class="fa fa-tag"></i> <span class="hidden-xs hidden-sm hidden-md">Tipos de Beneficiarios</span>
								</a>
							</li>
							@endpermission
							@permission('proyectos-documentacion-listar')
							<li>
								<a href="#documentacion" data-toggle="tab">
									<i class="fa fa-files-o"></i> <span class="hidden-xs hidden-sm hidden-md">Documentacion</span>
								</a>
							</li>
							@endpermission
							@permission('planes-comerciales-listar')
							<li>
								<a href="#planes" data-toggle="tab">
									<i class="fa fa-suitcase"></i> <span class="hidden-xs hidden-sm hidden-md">Planes Comerciales</span>
								</a>
							</li>
							@endpermission
							@permission('proyectos-metas-listar')
							<li>
								<a href="#metas" data-toggle="tab">
									<i class="fa fa-users"></i> <span class="hidden-xs hidden-sm hidden-md">Metas</span>
								</a>
							</li>
							@endpermission
							@permission('proyectos-municipios-listar')
							<li>
								<a href="#municipios" data-toggle="tab">
									<i class="fa fa-map-marker"></i> <span class="hidden-xs hidden-sm hidden-md">Municipios</span>
								</a>
							</li>
							@endpermission
							@permission('proyectos-costos-listar')
							<li>
								<a href="#costos" data-toggle="tab">
									<i class="fa fa-dollar"></i> <span class="hidden-xs hidden-sm hidden-md">Costos</span>
								</a>
							</li>
							@endpermission
							@permission('proyectos-clausulas-listar')
							<li>
								<a href="#clausulas" data-toggle="tab">
									<i class="fa fa-gavel"></i> <span class="hidden-xs hidden-sm hidden-md">Clausula de Permanencia</span>
								</a>
							</li>
							@endpermission
							@permission('proyectos-preguntas-listar')
							<li>
								<a href="#preguntas" data-toggle="tab">
									<i class="fa fa-question-circle"></i> <span class="hidden-xs hidden-sm hidden-md">Preguntas</span>
								</a>
							</li>
							@endpermission

							@permission('contrato-archivo-ajax')
							<li>
								<a href="#contrato" data-toggle="tab" data-id="{{$proyecto->ProyectoID}}" class="showDocumento" data-documento="contrato">
									<i class="fa fa-file-pdf-o"></i> <span class="hidden-xs hidden-sm hidden-md">Contrato</span>
								</a>
							</li>

							@if($proyecto->acta_juramentada)
							<li>
								<a href="#acta_juramentada" data-toggle="tab" data-id="{{$proyecto->ProyectoID}}" class="showDocumento" data-documento="acta">
									<i class="fa fa-file-text-o"></i> <span class="hidden-xs hidden-sm hidden-md">Acta Juramentada</span>
								</a>
							</li>
							@endif
							@endpermission

							@permission('documental-proyectos-listar')
							<li>
								<a href="#gestionDocumental" data-toggle="tab">
									<i class="fa fa-gavel"></i> <span class="hidden-xs hidden-sm hidden-md">Gestion Documental</span>
								</a>
							</li>
							@endpermission


						</ul>
					</div>
					<div class="col-xs-10 col-sm-10  col-md-10 col-lg-10">
						<!-- Tab panes -->
						<div class="tab-content">
							<div class="tab-pane active" id="detalles">
								@include('adminlte::proyectos.partials.detalles')
							</div>
							@permission('facturacion-electronica-api-ver')
							<div class="tab-pane" id="api">
								@include('adminlte::proyectos.api.show')
							</div>
							@endpermission
							@permission('proyectos-tipos-beneficiarios-listar')
							<div class="tab-pane" id="tipos_beneficiarios">
								@include('adminlte::proyectos.tipos-beneficiarios.show')
							</div>
							@endpermission
							@permission('proyectos-documentacion-listar')
							<div class="tab-pane" id="documentacion">
								@include('adminlte::proyectos.documentacion.show')
							</div>
							@endpermission
							@permission('planes-comerciales-listar')
							<div class="tab-pane" id="planes">
								@include('adminlte::comercial.planes.show')
							</div>
							@endpermission
							@permission('proyectos-metas-listar')
							<div class="tab-pane" id="metas">
								@include('adminlte::proyectos.metas.show')
							</div>
							@endpermission
							@permission('proyectos-municipios-listar')
							<div class="tab-pane" id="municipios">
								@include('adminlte::proyectos.municipios.show')
							</div>
							@endpermission
							@permission('proyectos-costos-listar')
							<div class="tab-pane" id="costos">
								@include('adminlte::proyectos.costos.index')
							</div>
							@endpermission
							@permission('proyectos-clausulas-listar')
							<div class="tab-pane" id="clausulas">
								@include('adminlte::proyectos.clausulas.index')
							</div>
							@endpermission
							@permission('proyectos-preguntas-listar')
							<div class="tab-pane" id="preguntas">
								@include('adminlte::proyectos.preguntas.index')
							</div>
							@endpermission

							@permission('contrato-archivo-ajax')
							<div class="tab-pane" id="contrato">
								<iframe id="contrato_previsualizar" width="100%" height="700px"></iframe>
							</div>

							@if($proyecto->acta_juramentada)
							<div class="tab-pane showDocumento" id="acta_juramentada">
								<iframe id="acta_previsualizar" width="100%" height="700px"></iframe>
							</div>
							@endif
							@endpermission

							@permission('documental-proyectos-listar')
							<div class="tab-pane" id="gestionDocumental">
								@include('adminlte::proyectos.gestion-documental.index')
							</div>
							@endpermission
						</div>
					</div>
				</div>

				<div class="espera" style="display:none;">
					<i class="fa fa-refresh fa-spin"></i>
				</div>
			</div>

		</div>
	</div>
</div>

@permission('proyectos-metas-crear')
@include('adminlte::proyectos.metas.add')
@endpermission

@permission('proyectos-metas-editar')
@include('adminlte::proyectos.metas.edit')
@endpermission

@permission('facturacion-electronica-api-crear')
@include('adminlte::proyectos.api.add')
@endpermission

@if(!empty($proyecto->facturacion_api))
@permission('facturacion-electronica-api-editar')
@include('adminlte::proyectos.api.edit')
@endpermission
@endif

@permission('proyectos-tipos-beneficiarios-crear')
@include('adminlte::proyectos.tipos-beneficiarios.add')
@endpermission

@permission('proyectos-tipos-beneficiarios-editar')
@include('adminlte::proyectos.tipos-beneficiarios.edit')
@endpermission

@permission('proyectos-documentacion-crear')
@include('adminlte::proyectos.documentacion.add')
@endpermission

@permission('proyectos-documentacion-editar')
@include('adminlte::proyectos.documentacion.edit')
@endpermission

@permission('planes-comerciales-crear')
@include('adminlte::comercial.planes.add')
@endpermission

@permission('planes-comerciales-editar')
@include('adminlte::comercial.planes.edit')
@endpermission

@permission('proyectos-municipios-crear')
@include('adminlte::proyectos.municipios.add')
@endpermission

@permission('proyectos-municipios-editar')
@include('adminlte::proyectos.municipios.edit')
@endpermission

@permission('proyectos-costos-crear')
@include('adminlte::proyectos.costos.add')
@endpermission

@permission('proyectos-costos-editar')
@include('adminlte::proyectos.costos.edit')
@endpermission

@permission('proyectos-clausulas-crear')
@include('adminlte::proyectos.clausulas.add')
@endpermission

@permission('proyectos-clausulas-editar')
@include('adminlte::proyectos.clausulas.edit')
@endpermission

@permission('proyectos-preguntas-crear')
@include('adminlte::proyectos.preguntas.create')
@endpermission

@permission('proyectos-preguntas-editar')
@include('adminlte::proyectos.preguntas.edit')
@endpermission

@permission('documental-proyectos-crear')
@include('adminlte::proyectos.gestion-documental.create')
@endpermission

@permission('documental-proyectos-editar')
@include('adminlte::proyectos.gestion-documental.edit')
@endpermission

@include('adminlte::partials.modal_show_archivos')


@section('mis_scripts')

<script type="text/javascript">
	const proyecto = {
		!!$proyecto - > ProyectoID!!
	};
	var respuestas_array = [];

	function buscar_municipio(modal, departamento, municipio, proyectoid = null) {

		var parameters = {
			departamento_id: departamento,
			proyecto_id: proyectoid,
			'_token': $('input:hidden[name=_token]').val()
		};


		$.post('/estudios-demanda/ajax-municipios', parameters).done(function(data) {

			modal.find('#municipio').empty();
			modal.find('#municipio').append('<option value="">Elija un municipio</option>');

			$.each(data, function(index, municipiosObj) {
				modal.find('#municipio').append(`<option value="${(municipiosObj.id !== undefined)? municipiosObj.id : municipiosObj.MunicipioId}" ${(municipio != null)? (municipiosObj.MunicipioId == municipio)? 'selected' : '' : ''}>${municipiosObj.NombreMunicipio}</option>`);
			});

		}).fail(function(e) {
			alert('error');
		});
	}
</script>

<script type="text/javascript" src="/js/planes/edit.js"></script>
<script type="text/javascript" src="/js/proyectos/metas-edit.js"></script>
<script type="text/javascript" src="/js/proyectos/municipios-edit.js"></script>
<script type="text/javascript" src="/js/proyectos/costos-edit.js"></script>
<script type="text/javascript" src="/js/proyectos/clausulas-edit.js"></script>
<script type="text/javascript" src="/js/proyectos/preguntas-edit.js"></script>
<script type="text/javascript" src="/js/proyectos/plantilla-contrato.js"></script>
<script type="text/javascript" src="/js/proyectos/tipos-beneficiarios-edit.js"></script>
<script type="text/javascript" src="/js/proyectos/documentacion-edit.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.js"></script>
<script type="text/javascript" src="/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="/js/dataTables.bootstrap.min.js"></script>

<script type="text/javascript" src="/js/myfunctions/show-archivo.js"></script>

<script type="text/javascript" src="/js/myfunctions/buscarmunicipios3.js"></script>


@if(!empty($proyecto->facturacion_api))
@permission('facturacion-electronica-api-eliminar')
<script>
	$('#eliminar-api').on('click', function() {
		if (confirm('Estas seguro de eliminar?')) {
			var parameters = {
				'_method': 'delete',
				'_token': $('input:hidden[name=_token]').val()
			}

			$.post('{{route("facturacion_electronica_api.destroy", $proyecto->facturacion_api->id)}}', parameters).done(function(data) {

				if (data) {
					toastr.options.positionClass = 'toast-bottom-right';
					toastr.success("API eliminada correctamente.");
					location.reload();
				} else {
					toastr.options.positionClass = 'toast-bottom-right';
					toastr.warning(data);
				}

			}).fail(function(e) {
				toastr.options.positionClass = 'toast-bottom-right';
				toastr.error(e.statusText);
			});
		}
	});
</script>
@endpermission
@endif

<script type="text/javascript">
	(function() {
		$('.js-example-basic-multiple').select2();
		$(".select2-container").attr('style', "width:100%");

		$('.js-example-basic-multiple-respuestas').select2({
			placeholder: "Ingrese las respuestas separadas por coma ','",
			tags: true,
			tokenSeparators: [',']
		});

		const valor1 = "{!! (isset($_GET['categoria'])? $_GET['categoria']: null) !!}";
		const valor2 = "{!! (isset($_GET['sub_categoria'])? $_GET['sub_categoria']: null) !!}";

		if (valor2.length > 0) {
			traer_sub_categorias(valor1, valor2);
		} else if (valor1.length > 0) {
			traer_sub_categorias(valor1);
		}
	})();


	$('#addMunicipio').find("#departamento").change(function() {
		buscar_municipio($('#addMunicipio'), $(this).val());
	});
</script>

<script type="text/javascript">
	$('#documentalEdit').on('show.bs.modal', function(event) {
		var a = $(event.relatedTarget) // Button that triggered the modal
		var id = a.data('id');
		var tipo = a.data('tipo');
		
		
		var url = tipo === 'CARPETA' ? '/documental-carpetas/' + id : '/documental-proyectos/' + id;
		var modal = $(this)

		modal.find('select[name=tipos]').prop('selectedIndex', 0)

		$.get(url + '/edit', null, function(data) {
			modal.find('form').attr('action', url);
			modal.find('input[name=nombre]').val(data['nombre']);			
			modal.find('select[name=tipo] option[value=' + (data['tipo'] === undefined ? 'CARPETA' : data['tipo']) + ']').prop("selected", true);
		});
	});
</script>
@endsection
@endsection