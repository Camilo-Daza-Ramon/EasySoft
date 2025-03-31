@if ($factura->Internet > 0)
  <tr>
  	<td>{{$i}}</td>
  	<td>{{$factura->cliente->plancomercial->nombre}} ({{$factura->cliente->plancomercial->DescripcionPlan}})</td>
  	<td>1</td>
  	<td>${{number_format($factura->Internet, 2, ',', '.')}}</td>
  	<td>0</td>
  	<td>${{number_format($factura->Internet, 2, ',', '.')}}</td>
  	<?php $i += 1; $total = $total + $factura->Internet; ?>
  </tr>
@endif

@if ($factura->Otro > 0)
<tr>
	<td>{{$i}}</td>
	<td>Otros Cobros (Reconexion)</td>
	<td>1</td>
	<td>${{number_format($factura->Otro, 0, ',', '.')}}</td>
	<td>0</td>
	<td>${{number_format($factura->Otro, 0, ',', '.')}}</td>
	<?php $i += 1; $total = $total + $factura->Otro; ?>
</tr>
@endif              


@if ($factura->SaldoEnMora > 0)
	<tr>
  	<td>{{$i}}</td>
  	<td>Saldo en mora</td>
  	<td>1</td>
  	<td>${{number_format($factura->SaldoEnMora, 0, ',', '.')}}</td>
  	<td>0</td>
  	<td>${{number_format($factura->SaldoEnMora, 0, ',', '.')}}</td>
  	<?php $i += 1; $total = $total + $factura->SaldoEnMora; ?>
  </tr>
@endif

@if ($factura->ValorCuota > 0)
<tr>
  <td>{{$i}}</td>
  <td>Cuota de Traslado</td>
  <td>1</td>
  <td>${{number_format($factura->ValorCuota, 0, ',', '.')}}</td>
  <td>${{number_format($factura->Iva, 0, ',', '.')}}</td>
  <td>${{number_format($factura->ValorCuota + $factura->Iva, 0, ',', '.')}}</td>
  <?php $i += 1;$total = $total + $factura->ValorCuota + $factura->Iva; ?>
</tr>
@endif

@if ($factura->SaldoEnMora < 0)

  @if(($factura->SaldoEnMora * -1) >= $total)
  <tr>
    <td>{{$i}}</td>
    <td>Saldo a favor</td>
    <td>1</td>
    <td>$-{{number_format($total, 0, ',', '.')}}</td>
    <td>0</td>
    <td>$-{{number_format($total, 0, ',', '.')}}</td>
    <?php $i += 1; $total = $total - $total; ?>
  </tr>
  @else
  <tr>
    <td>{{$i}}</td>
    <td>Saldo a favor</td>
    <td>1</td>
    <td>${{number_format($factura->SaldoEnMora, 0, ',', '.')}}</td>
    <td>0</td>
    <td>${{number_format($factura->SaldoEnMora, 0, ',', '.')}}</td>
    <?php $i += 1; $total = $total + $factura->SaldoEnMora; ?>
  </tr>
  @endif		            	
@endif


@if ($factura->AjustesPorFaltaDeServicio < 0)

  @if(($factura->AjustesPorFaltaDeServicio * -1) >= $total)
    <tr>
      <td>{{$i}}</td>
      <td>Ajustes por falta de servicio</td>
      <td>1</td>
      <td>$-{{number_format($total, 0, ',', '.')}}</td>
      <td>0</td>
      <td>$-{{number_format($total, 0, ',', '.')}}</td>
      <?php $i += 1; $total = $total - $total; ?>
    </tr>
  @else
    <tr>
    	<td>{{$i}}</td>
    	<td>Ajustes por falta de servicio</td>
    	<td>1</td>
    	<td>${{number_format($factura->AjustesPorFaltaDeServicio, 0, ',', '.')}}</td>
    	<td>0</td>
    	<td>${{number_format($factura->AjustesPorFaltaDeServicio, 0, ',', '.')}}</td>
    	<?php $i += 1; $total = $total + $factura->AjustesPorFaltaDeServicio; ?>
    </tr>
  @endif
@endif

@if ($factura->ValorCuota < 0)
<tr>
	<td>{{$i}}</td>
	<td>Otros descuentos</td>
	<td>1</td>
	<td>${{number_format($factura->ValorCuota, 0, ',', '.')}}</td>
	<td>0</td>
	<td>${{number_format($factura->ValorCuota, 0, ',', '.')}}</td>
	<?php $i += 1; $total = $total + $factura->ValorCuota; ?>
</tr>
@endif		            


@if ($factura->Otro < 0)

  @if(($factura->Otro * -1) >= $total)
    <tr>
      <td>{{$i}}</td>
      <td>Otros Descuentos</td>
      <td>1</td>
      <td>$-{{number_format($total, 0, ',', '.')}}</td>
      <td>0</td>
      <td>$-{{number_format($total, 0, ',', '.')}}</td>
      <?php $i += 1; $total = $total - $total; ?>
    </tr>
    @else
    <tr>
      <td>{{$i}}</td>
      <td>Otros Descuentos</td>
      <td>1</td>
      <td>${{number_format($factura->Otro, 0, ',', '.')}}</td>
      <td>0</td>
      <td>${{number_format($factura->Otro, 0, ',', '.')}}</td>
      <?php $i += 1; $total = $total + $factura->Otro; ?>
    </tr>
    @endif
@endif