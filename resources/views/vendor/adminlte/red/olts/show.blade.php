@extends('adminlte::layouts.app')

@section('contentheader_title')
    <h1> <i class="fa fa-user">  </i> Cliente ONLINE</h1>
@endsection


@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">
      <div class="col-md-12">

      	<table class="table">
      		<thead>
      			<tr>
      				<th>CEDULA</th>
      				<th>ID-PUNTO</th>
      				<th>DANE-MUNICIPIO</th>
      				<th>DANE-DEPARTAMENTO</th>
      				<th>ESTADO</th>			
      			</tr>
      		</thead>
      		<tbody>
      			@foreach($datos as $data)
      				
	      			<tr>
	      				<td>{{$data['cedula']}}</td>
	      				<td>{{$data['id_Beneficiario']}}</td>
	      				<td>{{$data['codDaneMuni']}}</td>
	      				<td>{{$data['codDaneDepar']}}</td>
	      				<td>{{$data['estado']}}</td>
	      			</tr>
      			@endforeach
      		</tbody>
      	</table>



        
      </div>
		</div>    
	</div>
  @section('mis_scripts')

  @endsection
@endsection


