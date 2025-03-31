<table class="table table-striped">
	<tr>
		<th>Tikect #</th>
		<th>Fecha Apetura</th>
		<th>Fecha de Cierre</th>
		<th>Estado</th>
		<th>Total Días</th>
	</tr>
	@foreach($cliente->tikect as $dato)

	
		@if($dato->EstadoDeTicket <> 0)
			@section('other-notifications')
			<div class="alert alert-warning alert-dismissible">
			    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			      <h4><i class="icon fa fa-warning"></i> Atención!</h4>
			      <p>El cliente tiene un ticket abierto <a href="/tickets/{{$dato->TicketId}}" target="_black"><b>#{{$dato->TicketId}}</b></a></p>
			  </div>
			@endsection
		@endif

	
	<tr>
		<td><a href="{{route('tickets.show', $dato->TicketId)}}" target="_black">{{$dato->TicketId}}</a></td>
		<td>{{$dato->FechaApertura}}</td>
		<td>{{$dato->FechaCierre}}</td>
		<td>{{$dato->estado->Descripcion}}</td>
		<td>
			<?php 
			$contador = date_diff(date_create($dato->FechaApertura), date_create($dato->FechaCierre));
			?>
			{{$contador->format('%a')}} Días sin solución
		</td>
	</tr>
	@endforeach
</table>