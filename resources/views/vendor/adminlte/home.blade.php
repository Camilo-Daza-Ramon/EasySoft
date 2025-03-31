@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1> <i class="fa fa-tasks">  </i> Home</h1>
@endsection


@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">

			@include('adminlte::partials.comun')

			@if(!isset($_GET['cedula']))
				@permission('dashboard-admin')
					@include('adminlte::partials.admin')
				@endpermission
			@endif
			
			@permission('dashboard-noc')
				@include('adminlte::partials.noc')
			@endpermission

			@permission('dashboard-comercial')
				@include('adminlte::partials.comercial')
			@endpermission

			@permission('dashboard-vendedor')
				@include('adminlte::partials.vendedor')
			@endpermission

			@permission('dashboard-tecnico')
				@include('adminlte::partials.tecnico')
			@endpermission
			
			


			@if(isset($_GET['cedula']))
				@if(count($cliente) > 0)
					@foreach($cliente as $dato)
					<div class="row">					
						<div class="col-md-12 col-sm-12 col-xs-12">
							<a href="{{route('clientes.show', $dato->ClienteId)}}">
								<div class="info-box">
									<span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span>

									<div class="info-box-content">
										<span class="info-box-number">CLIENTES</span>
										<span class="info-box-text">{{mb_convert_case($dato->NombreBeneficiario . ' ' . $dato->Apellidos, MB_CASE_TITLE, "UTF-8")}}</span>
										<span class="info-box-text">{{$dato->municipio->NombreMunicipio}} - {{$dato->municipio->departamento->NombreDelDepartamento}}</span>			              
									</div>
									<!-- /.info-box-content -->
								</div>
								<!-- /.info-box -->
							</a>			        
						</div>

						@if(count($dato->instalacion) > 0)
						<div class="col-md-12 col-sm-12 col-xs-12">
							<a href="{{route('instalaciones.index')}}{{'?documento='.$dato->Identificacion}}">
								<div class="info-box">
									<span class="info-box-icon bg-aqua"><i class="fa fa fa-hdd-o"></i></span>

									<div class="info-box-content">
										<span class="info-box-number">INSTALACIONES</span>
										<span class="info-box-text">{{mb_convert_case($dato->NombreBeneficiario . ' ' . $dato->Apellidos, MB_CASE_TITLE, "UTF-8")}}</span>
										<span class="info-box-text">{{$dato->municipio->NombreMunicipio}} - {{$dato->municipio->departamento->NombreDelDepartamento}}</span>			              
									</div>
									<!-- /.info-box-content -->
								</div>
								<!-- /.info-box -->
							</a>			        
						</div>
						@endif

						@if(count($dato->tikect) > 0)
						<div class="col-md-12 col-sm-12 col-xs-12">
							<a href="{{route('tickets.index')}}{{'?documento='.$dato->Identificacion}}">
								<div class="info-box">
									<span class="info-box-icon bg-aqua"><i class="fa fa fa-wrench"></i></span>

									<div class="info-box-content">
										<span class="info-box-number">TICKETS</span>
										<span class="info-box-text">{{mb_convert_case($dato->NombreBeneficiario . ' ' . $dato->Apellidos, MB_CASE_TITLE, "UTF-8")}}</span>
										<span class="info-box-text">{{$dato->municipio->NombreMunicipio}} - {{$dato->municipio->departamento->NombreDelDepartamento}}</span>			              
									</div>
									<!-- /.info-box-content -->
								</div>
								<!-- /.info-box -->
							</a>			        
						</div>
						@endif		        
					</div>
					@endforeach
				@else
					<h2>No hay datos.</h2>
				@endif
			@endif
		</div>
	</div>	
@endsection

