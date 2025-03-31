@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1><i class="fa fa-question"></i>  Novedades</h1>
@endsection

@section('main-content')
    <div class="container-fluid spark-screen">    	
        <div class="row">        	
            <div class="col-md-8">
            	<div class="box box-primary">
		            <div class="box-header with-border bg-blue">
		              <h3 class="box-title"><i class="fa fa-plus"></i> Agregar Novedad</h3>
		            </div>
		            <!-- /.box-header -->
		            <!-- form start -->
		            <form role="form" action="{{route('facturacion.novedades.store')}}" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
				        {{csrf_field()}}
		              <div class="box-body">
		                <div class="form-group">
		                	<label>Subir Archivo</label>
		                	<input type="file" name="archivo" class="form-control" value="{{old('archivo')}}" accept="text/plain, .csv" required>
		                	<span>-Archivo CSV separados por comas</span>
		              	</div>

		                <div class="form-group col-md-6">
		                	<label>Periodo Contable</label>
		            		<input type="month" class="form-control" name="periodo" value="{{old('periodo')}}" required>
		                </div>

		                <div class="form-group col-md-6">
		                <label>Tipo.</label>
			            	<select class="form-control" name="tipo" required>
			            		<option value="">Elija una opción</option>
			            		<option value="reconexion">Reconexión</option>
			            		<option value="suspendidos">Suspendidos</option>
			            	</select>
			            </div>
		              </div>
		              <!-- /.box-body -->

		              <div class="box-footer">
		                <button type="submit" class="btn btn-primary">Guardar</button>
		              </div>
		            </form>
          		</div>
		    </div>


		    <div class="col-md-4">
            	<div class="box box-primary">
		            <div class="box-header with-border bg-blue">
		              <h3 class="box-title"><i class="fa fa-plus"></i> Resultado</h3>
		            </div>
		            <div class="box-body">
		            	@if(Session::has('total_registros'))
		            	<table class="table">
		            		<tr>
		            			<th>Total de Registros:</th>
		            			<td>{{Session::get('total_registros')}}</td>
		            		</tr>
		            		<tr>
		            			<th>Registros Exitosos:</th>
		            			<td>{{Session::get('total_exitosos')}}</td>
		            		</tr>
		            		<tr>
		            			<th>Registros Erroneos:</th>
		            			<td>{{Session::get('total_errores')}}</td>
		            		</tr>
		            		<tr>
		            			<th>Cedulas con errores:</th>
		            			<td>
		            				@foreach(Session::get('cedulas') as $cedula)
		            				<p>{{$cedula}}</p>
		            				@endforeach
		            			</td>
		            		</tr>
		            	</table>
		            	@endif
		            </div>
		        </div>
		    </div>



        </div>
    </div>
@endsection