<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>CONCEPTO</th>
			<th>FECHA</th>
			<th>PERIODO</th>
			<th>FACTURA</th>
			<th>PAGO</th>
			<th>MORA</th>
			<th>Acciones</th>	
		</tr>
	</thead>
	<tbody>
		<?php $total_deuda = $total_mora = $total_facturado = $total_pagos = 0; ?>
		@foreach($recaudos as $factura)
			@if($factura->concepto == 'FACTURA')
			<tr style="{!!($factura->estado == 'ANULADA')? 'text-decoration: line-through;' : ''!!}">
				<th>
					<a href="{{route('facturacion.show', [$factura->periodo, $factura->id])}}" target="_black">{{$factura->id}}</a>
				</th>
				<td>{{$factura->concepto}}</td>
				<td>{{$factura->fecha}}</td>
				<td>{{strtoupper(strftime("%B %Y",strtotime(substr($factura->periodo,0,4) .'-'. substr($factura->periodo,4))))}}</td>
				<td>${{number_format($factura->valor,2, ',','.')}}</td>
				<td></td>
				<td>${{number_format($factura->mora,2, ',','.')}}</td>
				<td>
					@if(!empty($factura->factura_archivo))
                    	<a href="{{$factura->factura_archivo}}" class="btn btn-success btn-xs" title="Descargar" target="_black"><i class="fa fa-download"></i></a>
                    @endif
				</td>
			</tr>
			<?php $total_facturado += $factura->valor; 

				
					$total_mora +=  $factura->mora;
				
			?>
			@else
			<tr>
				<th>
					{{$factura->id}}
				</th>
				<td>{{$factura->concepto}}</td>
				<td>{{$factura->fecha}}</td>
				<td></td>
				<td></td>
				<td>${{number_format($factura->valor,2, ',','.')}}</td>
				<td></td>
				<td></td>
			</tr>
			<?php $total_pagos += $factura->valor; ?>
			@endif
		@endforeach
	</tbody>
	<tfoot>
		
		<tr>
			<th colspan="4" class="text-right">Total Deuda:</th>
			@if(isset($cliente->historial_factura_pago))
			<td >${{number_format($cliente->historial_factura_pago->total_deuda,2, ',', '.')}}</td>
			@else
			<td>$0.00</td>
			@endif
			<td colspan="3"></td>
		</tr>
	</tfoot>
</table>