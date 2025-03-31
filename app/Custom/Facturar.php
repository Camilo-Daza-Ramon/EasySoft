<?php 

namespace App\Custom;
use App\Facturacion;
use App\FacturaItem;
use App\HistorialFacturaPagoV;
use App\Cliente;
use App\Recaudo;
use App\ClienteContrato;
use App\ContratoServicio;
use App\Novedad;
use App\FacturaNovedad;
use DB;

class Facturar
{
	private function consulta_primer_factura($cliente_id){

		$cantidad = Facturacion::where('ClienteId', $cliente_id)->whereNull('estado')->count();
		return $cantidad;
	}

	private function facturar_primer_vez($cliente_id,$fecha_instalacion,$periodo,$tarifa,$estrato,$estado_servicio,$cobrar_iva){
		
		$concepto_array = array();

		$date1 = new \DateTime($fecha_instalacion);

		$ultimo_dia_mes = date("t", strtotime($periodo));

		if ($ultimo_dia_mes > 30) {
		    $ultimo_dia_mes = 30;
		}

		$date2 = new \DateTime(date("Y-m", strtotime($periodo)) . "-".$ultimo_dia_mes);

		//$date2 = new \DateTime(date("Y-m-t", strtotime($periodo)));

		$adicional = 0;

		#Cuando el mes sea febrero realiza la operacion para determinar la cantidad de  dias que hace falta para completar los 30 del servicio de internet.
		if(date("m", strtotime($periodo)) == "02" || date("Y-m", strtotime($fecha_instalacion)) == date('Y').'-02'){
		    $adicional = 30 - intval(date("t", strtotime($periodo)));
		}

		$diferencia = $date1->diff($date2);
		

		$valor_tarifa_dia = round($tarifa/30, 2);
		$iva = 0;

		if ($cobrar_iva) {
			if ($estrato == 'VIP' || $estrato == 1 || $estrato == 2) {
				$iva = 0;
			}else{
				$iva = 19;
			}
		}

		if ($diferencia->m > 0) {
			
			for ($i = 0; $i <= $diferencia->m; $i++) {
			    
			    $item = array();
			    
			    date("Y-m-t",strtotime($fecha_instalacion."+ ".$i." month"));	    
			    
			    
			    if ($i == 0){
			        
			        $total_dias_servicio = $diferencia->d + $adicional;

			        if ($total_dias_servicio == 0) {
        	        	continue;
        	        }

			        $total_pagar_servicio = $total_dias_servicio * $valor_tarifa_dia;
			        $item['cantidad'] = $total_dias_servicio;
			        $item['valor_unidad'] = $valor_tarifa_dia;
			        
			    }else{
			        $total_pagar_servicio = 30 * $valor_tarifa_dia;
			        $item['cantidad'] = 1;
			        $item['valor_unidad'] = $total_pagar_servicio;
			    }
			    
			    $item['concepto'] = 'Servicio de Internet' . ' ' . strtoupper(strftime("%B %Y",strtotime(date("Y-m-t",strtotime($fecha_instalacion."+ ".$i." month")))));
		    	$item['iva'] = $iva;
		    	$item['valor_iva'] = $total_pagar_servicio * ($iva / 100);
		    	$item['total'] = ($total_pagar_servicio * ($iva / 100)) + $total_pagar_servicio;
		    	
		    	$concepto_array[] = $item;
		    	
			}

		}elseif ($diferencia->d > 0) {
			$item = array();
			$total_dias_servicio = $diferencia->d + $adicional;
			$total_pagar_servicio = $total_dias_servicio * $valor_tarifa_dia;
			$item['cantidad'] = $total_dias_servicio;
			$item['valor_unidad'] = round($valor_tarifa_dia, 2);
			$item['unidad_medida'] = "DIAS";
			$item['concepto'] = 'Servicio de Internet' . ' ' . strtoupper(strftime("%B %Y",strtotime(date("Y-m-t",strtotime($fecha_instalacion)))));
	    	$item['iva'] = $iva;
	    	$item['valor_iva'] = round($total_pagar_servicio * ($iva / 100), 2);
	    	$item['total'] = round(($total_pagar_servicio * ($iva / 100)) + $total_pagar_servicio, 2);
	    	
	    	$concepto_array[] = $item;
		}{

		}

		return $concepto_array;
	}

	private function facturar_mes($cliente_id,$periodo,$tarifa,$estrato,$estado_servicio,$cobrar_iva,$fecha_instalacion, $ultima_factura, $tipo_cobro){

		$total_dias_servicio = 30;

		$valor_tarifa_dia = $tarifa/30;
		$concepto_array = array();
		
		

		$iva = 0;

		if ($cobrar_iva) {
			if ($estrato == 'VIP' || $estrato == 1 || $estrato == 2 || $estrato == 0) {
				$iva = 0;
			}elseif($estrato > 2){
				$iva = 19;
			}
		}

		if($tipo_cobro == "VENCIDO" && (strtotime($periodo) < strtotime($fecha_instalacion))){

			
		}else{

			//if ($ultima_factura == 0) {

				if(date("Y-m", strtotime($periodo)) == date("Y-m", strtotime($fecha_instalacion))){

					$date1 = new \DateTime($fecha_instalacion);
					$date2 = new \DateTime(date("Y-m-t", strtotime($periodo)));
					$adicional = 0;
					
					#Cuando el mes sea febrero realiza la operacion para determinar la cantidad de  dias que hace falta para completar los 30 del servicio de internet.
					if(date("m", strtotime($periodo)) == "02"){
						$adicional = 30 - intval(date("t", strtotime($periodo)));
					}else{
						$date2 = new \DateTime(date("Y-m-"."30", strtotime($periodo)));
					}
					
					$diferencia = $date1->diff($date2);
					
					$total_dias_servicio =  ($diferencia->d + $adicional);

				}

				$total_pagar_servicio = $valor_tarifa_dia * $total_dias_servicio;

			//}
			
			$concepto_array['concepto'] = 'Servicio de Internet' . ' ' . strtoupper(strftime("%B %Y",strtotime(date("Y-m-t",strtotime($periodo)))));
			$concepto_array['cantidad'] = 1;
			$concepto_array['valor_unidad'] = round($total_pagar_servicio,2);
			$concepto_array['unidad_medida'] = "MES";
			$concepto_array['iva'] = $iva;
			$concepto_array['valor_iva'] = round($total_pagar_servicio * ($iva / 100), 2);
			$concepto_array['total'] = round(($total_pagar_servicio * ($iva / 100)) + $total_pagar_servicio, 2);

		}	

		return $concepto_array;
	}


	private function mora($cliente_id){

		$deuda_cliente = HistorialFacturaPagoV::select('total_deuda')->where('ClienteId', $cliente_id)->first();
		$concepto_array = array();
		$mora = 0;

		if (!empty($deuda_cliente)) {

			
			if (!empty($deuda_cliente->total_deuda)) {
				$mora = $deuda_cliente->total_deuda;
			}else{
				$mora = 0;
			}

			if ($mora == 0) {
				
			}else{		

				if ($mora < 0) {
					$concepto_array['concepto'] = 'Saldo a Favor';
				}else{
					$concepto_array['concepto'] = 'Saldo en Mora';
				}

				$concepto_array['cantidad'] = 1;
				$concepto_array['valor_unidad'] = $mora;
				$concepto_array['unidad_medida'] = "UNIDAD";
				$concepto_array['iva'] = 0;
				$concepto_array['valor_iva'] = 0;
				$concepto_array['total'] = $mora;

				return $concepto_array;
			}		

		}
	}

	private function ultima_factura($cliente_id, $periodo, $proyecto){

		$ultimo_periodo = date("Y-m",strtotime($periodo."- 1 month"));

		$ultima = Facturacion::where([
			['ClienteId', $cliente_id],
			['Periodo', intval(str_replace('-', '',$ultimo_periodo))],
			['ProyectoId', $proyecto]
		])
		->whereNull('estado')
		->count();

		return $ultima;
	}

	private function ultimo_pago($cliente_id, $periodo){

		$desde = $periodo."-01";

		$hasta = date("Y-m-t",strtotime($desde."- 1 month"));

		$pago = Recaudo::selectRaw('SUM(valor) as total')
		->where('ClienteId', $cliente_id)
		->whereBetween('Fecha', [$desde, $hasta])
		->groupBy('ClienteId')
		->get();

		if (!empty($pago[0])) {
			$pago = $pago[0]->total;
		}else{
			$pago = 0;
		}

		return $pago;
	}

	private function ultimo_pago2($cliente_id){

		$pago = Recaudo::select('valor','Fecha')
		->where('ClienteId', $cliente_id)		
		->orderBy('RecaudoId','DESC')
		->first();

		$resultado = array('valor' => 0, 'fecha' => null);

		if (count($pago) > 0) {
			$resultado['valor'] = floatval($pago->valor);
			$resultado['fecha'] = $pago->Fecha;
		}

		return $resultado;
	}

	private function novedades($cliente_id, $periodo, $tarifa){

		$valor_tarifa_minuto = ($tarifa/30) / 1440;
		$minutos_sin_servicio = 0;

		$periodo = date("Y-m",strtotime($periodo."- 1 month"));

		$novedades1 = Novedad::where([
			['ClienteId',$cliente_id],
			['cobrar', true],
			['estado', 'PENDIENTE'], 
			['fecha_fin', '<=', date("Y-m-t H:i:s", strtotime($periodo.'-01 23:59:59'))]
		]);

		$novedades2 = Novedad::where([
			['ClienteId', $cliente_id],
			['cobrar', false]									
		])
		//->where('fecha_inicio','<', $periodo.'-01')
		->where('fecha_inicio','<', date("Y-m-t",strtotime($periodo)))
		->whereIn('concepto', ['Suspensión Temporal', 'Suspensión por Mora', 'Ajustes por falta de servicio'])
		->whereNull('fecha_fin');

		$novedades2_1 = Novedad::where([
			['ClienteId', $cliente_id],
			['cobrar', false]									
		])
		//->where('fecha_inicio','<', $periodo.'-01')
		->where([
			['fecha_inicio','<', date("Y-m-t",strtotime($periodo))], 
			['fecha_fin', '>', date("Y-m") . '-01 00:00:00']
		])
		->whereIn('concepto', ['Suspensión Temporal', 'Suspensión por Mora', 'Ajustes por falta de servicio']);

		$novedades3 = Novedad::where([
			['ClienteId', $cliente_id],
			['cobrar', false]
		])
		->whereIn('concepto', ['Suspensión Temporal', 'Suspensión por Mora', 'Ajustes por falta de servicio'])
		->whereIn('estado', ['PENDIENTE', 'SALDADO'])
		//->whereBetween('fecha_fin', [$periodo.'-01', date("Y-m-t", strtotime($periodo.'-01'))])
		->whereBetween('fecha_fin', [$periodo.'-01 00:00:00', date("Y-m") . '-01 00:00:00'])
		->union($novedades1)
		->union($novedades2)
		->union($novedades2_1)
		->get();

		
		
		$conceptos = array();

		if (!empty($novedades3)) {

			foreach ($novedades3 as $novedad) {

				$date2 = "";
				$concepto = "";
	        	$cantidad = 0;
	        	$valor_unidad = 0;		        	
	        	$iva = 0;
	        	$total_iva = 0;
	        	$valor_total = 0;

	        	//460059

				if (($novedad->concepto == 'Suspensión por Mora' || $novedad->concepto == 'Suspensión Temporal' || $novedad->concepto == 'Ajustes por falta de servicio') && (!$novedad->cobrar) ) {
					
					$date1 = new \DateTime($novedad->fecha_inicio);

					if (empty($novedad->fecha_fin)) {
						$date2 = new \DateTime(date("Y-m-t H:i:s", strtotime($periodo . ' 23:59:59') ));
					}else{

						if($novedad->fecha_fin > date("Y-m-t H:i:s", strtotime($periodo . ' 23:59:59'))){

							//dd(date("Y-m-t H:i:s", strtotime($periodo . ' 23:59:59')));

					        $date2 = new \DateTime(date("Y-m-t H:i:s", strtotime($periodo . ' 23:59:59')));

					    }else{
					        $date2 = new \DateTime($novedad->fecha_fin);
					    }
					}		    		

		    		$diferencia = $date1->diff($date2);

		    		if(date('m', strtotime($novedad->fecha_inicio)) <> date('m', strtotime($periodo.'-01'))){
		    	    
		        	    $date1 = new \DateTime(date($periodo."-01 00:00:00"));
		        		$date2 = new \DateTime($novedad->fecha_fin);
		        		
		        		$diferencia = $date1->diff($date2);
		        	}

		        	//$dias_sin_servicio = ((($diferencia->m) + ($diferencia->y * 12)) * 30) + $diferencia->d;		        	

		        	/*if ($dias_sin_servicio > 30){
					    $dias_sin_servicio = 30;
					}*/

					$minutos_sin_servicio = ($diferencia->days * 1440) + ($diferencia->h * 60) + $diferencia->i;

					//dd($minutos_sin_servicio);

					if ($minutos_sin_servicio > 43200){
					    $minutos_sin_servicio = 43200;
					}

					if (($minutos_sin_servicio > 150) && ($minutos_sin_servicio < 43200)) {
						$minutos_sin_servicio -= 150;
					}

		        	$concepto = $novedad->concepto;
		        	$cantidad = $minutos_sin_servicio;
		        	$valor_unidad = $valor_tarifa_minuto * (-1);
		        	$valor_total = ($valor_tarifa_minuto * $minutos_sin_servicio) * (-1);

				}else{
					$concepto = $novedad->concepto;
		        	$cantidad = $novedad->cantidad;
		        	$valor_unidad = $novedad->valor_unidad;
		        	$iva = (!empty($novedad->iva))? $novedad->iva : 0;
		        	$total_iva = ($novedad->iva / 100) * ($novedad->cantidad * $novedad->valor_unidad);
		        	$valor_total = (($novedad->iva / 100) * ($novedad->cantidad * $novedad->valor_unidad)) + ($novedad->cantidad * $novedad->valor_unidad);
				}

				$concepto_array = array();
				$concepto_array['novedad_id'] = $novedad->id;
				$concepto_array['concepto'] = $concepto;
				$concepto_array['cantidad'] = $cantidad;
				$concepto_array['valor_unidad'] = round($valor_unidad,2);
				$concepto_array['unidad_medida'] = $novedad->unidad_medida;
				$concepto_array['iva'] = $iva;
				$concepto_array['valor_iva'] = $total_iva;
				$concepto_array['total'] = round($valor_total,2);
				$conceptos[] = $concepto_array;
			}
		}

		return $conceptos;	
		
	}

	private function servicio_anterior($cliente_id, $periodo_actual, $estrato, $id_contrato_actual,  $tarifa_actual = null){		
		$items = array();

		$periodo_anterior = date("Y-m",strtotime($periodo_actual."- 1 month"));


		$contrato_actual = ClienteContrato::findOrFail($id_contrato_actual);


		$contrato_anterior = ClienteContrato::where([
			['estado', 'FINALIZADO'],
			['ClienteId', $cliente_id]
		])
		->whereBetween("fecha_final", [$periodo_anterior."-01", date('Y-m-d')])->first();


		if(!empty($contrato_anterior)){

			$descuento = 0;
			$iva = 0;
			$dias_acobrar = 0;

			if ($estrato == 'VIP' || $estrato == 1 || $estrato == 2 || $estrato == 0) {
				$iva = 0;
			}elseif($estrato > 2){
				$iva = 19;
			}

			//comparamos si la fecha de finalización del contrato anterior es menor que el ultimo día del mes anterior
			if((strtotime($contrato_anterior->fecha_final) <= strtotime(date("Y-m-t", strtotime($periodo_anterior)))) && (strtotime($contrato_actual->fecha_instalacion) <= strtotime(date("Y-m-t", strtotime($periodo_anterior)))) && ($contrato_actual->fecha_instalacion != $contrato_anterior->fecha_final)){

				$date1 = new \DateTime($periodo_anterior."-01");
				$date2 = new \DateTime($contrato_anterior->fecha_final);
				$diferencia = $date1->diff($date2);

				//dd($diferencia->days);

				if($diferencia->days > 0 && $contrato_anterior->tipo_cobro == 'ANTICIPADO' && (date("Y-m", strtotime($contrato_actual->fecha_instalacion)) == $periodo_actual)){

					$descuento = 30 - ($diferencia->days + 1);
					$dias_acobrar = $diferencia->days + 1;

				}

			}elseif($contrato_actual->fecha_instalacion == $contrato_anterior->fecha_final){

				$date1 = new \DateTime(date("Y-m", strtotime($contrato_anterior->fecha_final))."-01");
				$date2 = new \DateTime($contrato_actual->fecha_instalacion);
				$diferencia = $date1->diff($date2);

				$dias_adicionales = 0;

				if($contrato_anterior->tipo_cobro == "VENCIDO" && date("Y-m", strtotime($contrato_anterior->fecha_final)) == $periodo_actual){
					$dias_adicionales = 30;
				}

				$dias_acobrar = (($diferencia->days > 0)? $diferencia->days + 1 : 0) + $dias_adicionales;

			}else{
				//Significa que la fecha de finalización fue en el mes actual.
				$date1 = new \DateTime($periodo_actual."-01");				
			}

			if(strtotime($contrato_actual->fecha_instalacion) > strtotime($contrato_anterior->fecha_final)){
				$date2 = new \DateTime($contrato_anterior->fecha_final);
				$diferencia = $date1->diff($date2);

				if($diferencia->days > 0){
					$dias_acobrar = $diferencia->days + 1;
				}
				
			}

			
			

			if($contrato_anterior->servicio->count() > 0){

				foreach ($contrato_anterior->servicio as $servicio) {						

					if($descuento > 0){

						$concepto_array = array();

						if($contrato_anterior->tipo_cobro == 'ANTICIPADO'){
							//En el caso de que el contrato anterior sea anticipado, significa que ya se cobro el mes completo y sebe descontar los valores proporcionales a la  nueva tarifa con los valores porporcioanes ya cobrados.
							$total = (($tarifa_actual/30) * $descuento) - (($servicio->valor/30) * $descuento);	
							$valor_tarifa_dia = round(($total/$descuento), 2);			
							$total_pagar_servicio = round(($valor_tarifa_dia * $descuento),2);
							
						}else{
							//En el caso de que no sea anticipada, significa que se deben cobrar los dias porporcioanles a la nueva tarifa.
							$valor_tarifa_dia = round(($tarifa_actual/30), 2);
							$total_pagar_servicio = round(($valor_tarifa_dia * $descuento), 2);
						}

						$concepto_array['concepto'] = "Valor Proporcional Tarifa Nueva";
						$concepto_array['cantidad'] = $descuento;
						$concepto_array['valor_unidad'] = $valor_tarifa_dia;
						$concepto_array['unidad_medida'] = "DIAS";
						$concepto_array['iva'] = $iva;
						$concepto_array['valor_iva'] = $total_pagar_servicio * ($iva / 100);
						$concepto_array['total'] = ($total_pagar_servicio * ($iva / 100)) + ($total_pagar_servicio);

						$items[] = $concepto_array;

					}

					if($dias_acobrar  > 0){

						if($contrato_anterior->tipo_cobro != 'ANTICIPADO' || (($contrato_actual->fecha_instalacion == $contrato_anterior->fecha_final) && (date("Y-m", strtotime($contrato_actual->fecha_instalacion)) == $periodo_actual))){

							$concepto_array = array();

							//Aquí cobramos los valores de los dias de la tarifa anterior
							$valor_tarifa_dia = round(($servicio->valor/30), 2);
							$total_pagar_servicio = round(($valor_tarifa_dia * $dias_acobrar), 2);		
							
							$concepto_array['concepto'] = "Valor Proporcional Tarifa Anterior";
							$concepto_array['cantidad'] = $dias_acobrar;
							$concepto_array['valor_unidad'] = $valor_tarifa_dia;
							$concepto_array['unidad_medida'] = "DIAS";
							$concepto_array['iva'] = $iva;
							$concepto_array['valor_iva'] = $total_pagar_servicio * ($iva / 100);
							$concepto_array['total'] = ($total_pagar_servicio * ($iva / 100)) + ($total_pagar_servicio);
							
							$items[] = $concepto_array;
						}elseif($contrato_anterior->tipo_cobro == 'ANTICIPADO' && (date("Y-m", strtotime($contrato_anterior->fecha_final)) == $periodo_actual)){

							$concepto_array = array();

							//Aquí cobramos los valores de los dias de la tarifa anterior
							$valor_tarifa_dia = round(($servicio->valor/30), 2);
							$total_pagar_servicio = round(($valor_tarifa_dia * $dias_acobrar), 2);						
							
							$concepto_array['concepto'] = "Valor Proporcional Tarifa Anterior";
							$concepto_array['cantidad'] = $dias_acobrar;
							$concepto_array['valor_unidad'] = $valor_tarifa_dia;
							$concepto_array['unidad_medida'] = "DIAS";
							$concepto_array['iva'] = $iva;
							$concepto_array['valor_iva'] = $total_pagar_servicio * ($iva / 100);
							$concepto_array['total'] = ($total_pagar_servicio * ($iva / 100)) + ($total_pagar_servicio);
							
							$items[] = $concepto_array;

						}
					}

				}
			}
			
		}

		return $items;

	}

	private function cobrar_mes_reactivacion($cliente_id, $periodo_anterior, $valor_tarifa, $iva, $tipo_cobro){
		/*SELECT n.* FROM novedades as n
		LEFT JOIN Facturacion as f ON n.ClienteId = f.ClienteId and f.Periodo = 202501
		where 
			n.fecha_fin between '2025-01-01 00:00:00' and '2025-01-31 23:59:59' 
			and n.concepto = 'Suspensión por Mora' 
			and n.ClienteId = 463072 
			and f.ClienteId is null*/

		$items = array();

		if($tipo_cobro != 'VENCIDO'){

			$valor_tarifa_dia = $valor_tarifa/30;

			$novedades = Novedad::select('novedades.*')->leftJoin('Facturacion as f', function ($join) use($periodo_anterior){
				$join->on('novedades.ClienteId', '=', 'f.ClienteId')->where('f.Periodo', str_replace("-", "", $periodo_anterior));
			})->where([
				['novedades.concepto', 'Suspensión por Mora'],
				['novedades.ClienteId', $cliente_id]
			])->whereBetween('novedades.fecha_fin', [$periodo_anterior.'-01 00:00:00', date("Y-m-t", strtotime($periodo_anterior)).' 23:59:59'])
			->whereNull('f.ClienteId')
			->get();

			if($novedades->count() > 0){

				$total_dias_sin_servicio = 0;

				foreach ($novedades as $novedad) {

					$fecha_inicio = new \DateTime($novedad->fecha_inicio);
					$date1 = new \DateTime($periodo_anterior."-01");

					if($fecha_inicio > $date1){
						$date1 = $fecha_inicio;
					}

					$date2 = new \DateTime($novedad->fecha_fin);
					$diferencia = $date1->diff($date2);

					$total_dias_sin_servicio += $diferencia->days + 1;

				}

				if($total_dias_sin_servicio <= 30){

					$dias_con_servicio = 30 - $total_dias_sin_servicio;
					$total_pagar_servicio = $dias_con_servicio * $valor_tarifa_dia;

					$concepto_array['concepto'] = 'Servicio de Internet' . ' ' . strtoupper(strftime("%B %Y",strtotime(date("Y-m-t",strtotime($periodo_anterior)))));
					$concepto_array['cantidad'] = $dias_con_servicio;
					$concepto_array['valor_unidad'] = $valor_tarifa_dia;
					$concepto_array['unidad_medida'] = "DIAS";
					$concepto_array['iva'] = $iva;
					$concepto_array['valor_iva'] = $total_pagar_servicio * ($iva / 100);
					$concepto_array['total'] = ($total_pagar_servicio * ($iva / 100)) + ($total_pagar_servicio);

					$items[] = $concepto_array;
					
				}	

			}
		}

		return $items;	


	}

	public function generar($parametros){		

		$result = DB::transaction(function () use($parametros) {

			$proyecto = $parametros['proyecto'];			
			$departamento = $parametros['departamento'];			
			$municipio = $parametros['municipio'];
			$periodo = $parametros['periodo'];
			$cedulas_facturar = $parametros['cedulas_facturar'];
			$cedulas_no_facturar = $parametros['cedulas_no_facturar'];

			$periodo_facturado = str_replace('-', '', $periodo);

			$clientes = Cliente::select('Clientes.Identificacion', 
						'Clientes.ClienteId', 
						'Clientes.UbicacionId', 
						'Clientes.ProyectoId', 
						'Clientes.NombreBeneficiario', 
						'Clientes.Apellidos',
						'Clientes.DireccionDeCorrespondencia', 
						'Clientes.Barrio',
						'Clientes.Estrato',
						'Municipios.NombreMunicipio',
						'Municipios.CodigoDane')
					    ->leftJoin('Facturacion', function ($join) use ($periodo_facturado){
	                        $join->on('Clientes.ClienteId', '=', 'Facturacion.ClienteId')
	                            ->where('Facturacion.Periodo', $periodo_facturado)
	                            ->whereNull('Facturacion.estado');

	                    })
	                    ->join('Municipios', 'Clientes.municipio_id', '=', 'Municipios.MunicipioId')	                    
	                    ->join('clientes_contratos', 'Clientes.ClienteId', 'clientes_contratos.ClienteId')

	                    ->where(function ($query) use($parametros) {

	                    	$departamento = $parametros['departamento'];			
							$municipio = $parametros['municipio'];

	                    	if (!empty($municipio)) {
		                    	$query->where('Clientes.municipio_id', $municipio);
		                    }else{
		                    	if (!empty($departamento)) {
			                    	$query->where('Municipios.DeptId', $departamento);
			                    }
		                    }
	                    })

	                    ->where(function ($query) use($parametros) {
	                    	$cedulas_facturar = $parametros['cedulas_facturar'];

	                    	if (!empty($cedulas_facturar)) {
	                    		if (strpos($cedulas_facturar, ",") !== false) {
								    $query->whereIn('Clientes.Identificacion', explode(",",$cedulas_facturar));
								}else{
									$query->whereIn('Clientes.Identificacion', [$cedulas_facturar]);
								}		                    	
		                    }
	                    })

	                    ->where(function ($query) use($parametros) {
	                    	$cedulas_no_facturar = $parametros['cedulas_no_facturar'];
	                    	
	                    	if (!empty($cedulas_no_facturar)) {
	                    		if (strpos($cedulas_no_facturar, ",") !== false) {
		                    		$query->whereNotIn('Clientes.Identificacion', explode(",",$cedulas_no_facturar));
		                    	}else{
									$query->whereNotIn('Clientes.Identificacion', [$cedulas_no_facturar]);
								}
		                    }
	                    })

	                    ->where(function ($query) use($parametros) {
	                    	$clasificacion = $parametros['clasificacion'];

	                    	if (!empty($clasificacion)) {
	                    		$query->where('Clientes.Clasificacion', $clasificacion);
	                    	}
	                    })

	                    ->where([
	                    	['Clientes.ProyectoId', $proyecto],
	                    	['Clientes.Status','ACTIVO'], 
	                    	['clientes_contratos.estado','VIGENTE']
	                    ])
	                    ->whereNull('Facturacion.ClienteId')	                    
						->get();

			foreach ($clientes as $cliente) {

				$tipo_cobro = "";
				$plan_contratado = "";
				$descripcion_plan = "";
				$fecha_instalacion = "";
				$conceptos = array();

				$contratos = ClienteContrato::
							where([
							 	['estado', 'VIGENTE'],
							 	['ClienteId', $cliente->ClienteId]
							])
							->get();

				$cantidad_facturas = $this->consulta_primer_factura($cliente->ClienteId);

				$iva = null;

				if ($cliente->Estrato == 'VIP' || $cliente->Estrato == 1 || $cliente->Estrato == 2 || $cliente->Estrato == 0) {
					$iva = 0;
				}elseif($estrato > 2){
					$iva = 19;
				}

				$periodo_facturado_tipo_contrato = '';
				$mes_facturado = $periodo;
				$valor_tarifa = 0;
				$seguir = true;
				$contrato_id = null;

				foreach ($contratos as $contrato) {

					$contrato_id = $contrato->id;

					$tipo_cobro = $contrato->tipo_cobro;
					$fecha_instalacion = $contrato->fecha_instalacion;
					$periodo_tipo_contrato = 0;

					switch ($tipo_cobro) {
			        	case 'ANTICIPADO':
			        		$mes_facturado = date("Y-m",strtotime($periodo));
			                $periodo_facturado_tipo_contrato = strtoupper(strftime("%B %Y",strtotime($periodo)));
			        		break;

			        	case 'VENCIDO':
			        		$mes_facturado = date("Y-m",strtotime($periodo."- 1 month"));
			            	$periodo_facturado_tipo_contrato = strtoupper(strftime("%B %Y",strtotime($periodo."- 1 month")));
			        		break;
			        }

					$servicios = ContratoServicio::
								where('contrato_id', $contrato->id)							
								->whereIn('estado', ['Suspendido', 'Activo'])
								->get();


					foreach ($servicios as $servicio) {

						$seguir = true;

						if($servicio->estado == 'Suspendido' && $tipo_cobro == 'VENCIDO'){
							$novedad_pendiente = Novedad::where([
								['estado', 'PENDIENTE'],
								['fecha_inicio', '>=', date("Y-m",strtotime($periodo."- 1 month")) . '-01'],
								['concepto', 'Suspensión por Mora'],
								['ClienteId', $cliente->ClienteId]
							])->count();

							if($novedad_pendiente  <= 0){
								$seguir = false;
								continue 3;
							}
						}else if($servicio->estado == 'Suspendido'){
							$seguir = false;
							continue 2;
						}


						$valor_tarifa = $servicio->valor;
						$plan_contratado .= $servicio->nombre. ' ';
						$descripcion_plan .= $servicio->descripcion. ' ';

						if ($cantidad_facturas > 0) {
							
							$cantidad_ultima_factura = $this->ultima_factura($cliente->ClienteId,$periodo, $cliente->ProyectoId);

							$resultado_servicio = $this->facturar_mes($cliente->ClienteId,$mes_facturado,$servicio->valor,$cliente->Estrato,$servicio->estado,$servicio->iva,$contrato->fecha_instalacion,$cantidad_ultima_factura, $tipo_cobro);

							if (!empty($resultado_servicio)){
								$conceptos[] = $resultado_servicio;
							}
							
						}else{

							$resultado_servicio = $this->facturar_primer_vez($cliente->ClienteId,$contrato->fecha_instalacion,$mes_facturado,$servicio->valor,$cliente->Estrato,$servicio->estado,$servicio->iva);								
						

							if (!empty($resultado_servicio)) {
								foreach ($resultado_servicio as $key) {
									$conceptos[] = $key;
								}
							}
						}
					}
				}

				if(!$seguir){
					continue;
				}

				$conceptos[] = $this->mora($cliente->ClienteId);

				$servicios_anteriores = $this->servicio_anterior($cliente->ClienteId, $periodo,$cliente->Estrato, $contrato_id, $valor_tarifa);

				if(!empty($servicios_anteriores)){
					foreach ($servicios_anteriores as $servicio_anterior) {
						$conceptos[] = $servicio_anterior;
					}					
				}				

				$cobrar_mes_anterior = $this->cobrar_mes_reactivacion($cliente->ClienteId, date("Y-m",strtotime($periodo."- 1 month")), $valor_tarifa, $iva, $tipo_cobro);

				if(!empty($cobrar_mes_anterior)){
					foreach ($cobrar_mes_anterior as $mes_anterior) {
						$conceptos[] = $mes_anterior;
					}					
				}

				$ultipoPago = $this->ultimo_pago($cliente->ClienteId, $periodo);

				$ultimoPago2 = $this->ultimo_pago2($cliente->ClienteId);

				$novedades_array = $this->novedades($cliente->ClienteId, $periodo, $valor_tarifa);

				if (!empty($novedades_array)) {

					foreach ($novedades_array as $key) {
						$conceptos[] = $key;


						$novedad_saldar = Novedad::find($key['novedad_id']);
						$novedad_saldar->estado = 'SALDADO';

						if (!$novedad_saldar->save()) {
							DB::rollBack();
						}					
						
					}
				}

				
				$valor_total_pagar = 0;
				$valor_total_iva = 0;
				$internet = 0;
				$saldo_mora = 0;
				$tiene_datos = 0;



				if (count($conceptos) > 0) {

					foreach ($conceptos as $key) {

						if (!empty($key)) {
							$tiene_datos += 1;
						}		

						switch (substr($key['concepto'], 0, 20)) {
							case 'Servicio de Internet':
								$internet += $key['total'];
								break;

							case 'Saldo en Mora':
								$saldo_mora = $key['total'];
								break;
						}

						$valor_total_pagar += ($key['total']);
						$valor_total_iva += $key['valor_iva'];
					}

					/*if ($valor_total_pagar < 0) {
						$valor_total_pagar = 0;
					}*/

					if ($tiene_datos > 0) {

						$factura = new Facturacion;
						$factura->ClienteId = $cliente->ClienteId; 
				        $factura->Periodo = str_replace('-', '', $periodo); 
				        $factura->Internet = $internet;
				        $factura->Antivirus = 0;
				        $factura->Telefonia = 0; 
				        $factura->Tv = 0;
				        $factura->Otro = 0;
				        $factura->Iva = $valor_total_iva;
				        $factura->NotaCredito = 0; 
				        $factura->AjustesPorFaltaDeServicio = 0; 
				        $factura->AjusteAlPeso = 0; 
				        $factura->ValorRecaudo = $ultipoPago; 
				        $factura->Traslado = 0; 
				        $factura->Saldo = 0; 
				        $factura->SaldoEnMora = $saldo_mora; 
				        $factura->ValorTotal = $valor_total_pagar;

				        $fecha = explode('-', $periodo);
				        $factura->Mes = $fecha[1];
				        $factura->Año = $fecha[0];
				        
				        $factura->PeriodoFacturado = "";

				        $factura->PeriodoFacturado = $periodo_facturado_tipo_contrato;

				        $mes_facturado = explode('-', $mes_facturado);
				        $factura->MesFacturado = $mes_facturado[1];
				        $factura->AñoFacturado = $mes_facturado[0];

				        $factura->ProyectoId = $cliente->ProyectoId; 
				        $factura->UbicacionId = $cliente->UbicacionId; 
				        $factura->NombreCliente = $cliente->NombreBeneficiario . ' ' . $cliente->Apellidos; 
				        $factura->Direccion = $cliente->DireccionDeCorrespondencia . ' ' . $cliente->Barrio; 
				        $factura->FechaEmision = date('Y-m-d');
				        $factura->Municipio = $cliente->NombreMunicipio; 
				        $factura->DiasDescontados = 0; 
				        $factura->HorasSinServicio = 0; 
				        $factura->Meta = 0;
				        $factura->Identificacion = $cliente->Identificacion; 
				        $factura->EmpresaFacturaId = 1; 
				        $factura->FechaInstalacion = $fecha_instalacion; 
				        $factura->NombreUbicacion = $cliente->Ubicación;
				        $factura->CodigoDane = $cliente->CodigoDane;
				         
				        $factura->PagoConDescuento = number_format($valor_total_pagar, 0,'','');
				        $factura->FechaDePago = $parametros['fecha_limite_pago'];
				        $factura->FechaDePagoConDescuento = $parametros['fecha_limite_pago'];
				        $factura->PeriodoServicio = $mes_facturado[0] . $mes_facturado[1]; 
				        $factura->ValorCuota = 0;
				        $factura->plan_contratado = $plan_contratado;
				        $factura->descripcion_plan = $descripcion_plan;
				        $factura->tipo_facturacion = $tipo_cobro;
				        $factura->ultimo_pago = $ultimoPago2['valor'];
				        $factura->fecha_ultimo_pago = $ultimoPago2['fecha'];

				        if ($factura->save()) {

				        	if (!empty($novedades_array)) {

								foreach ($novedades_array as $key) {

									if (array_key_exists('novedad_id',$key)) {
										$factura_novedad = new FacturaNovedad();
										$factura_novedad->factura_id = $factura->FacturaId;
										$factura_novedad->novedad_id = $key['novedad_id'];

										if (!$factura_novedad->save()) {
											DB::rollBack();
										}
									}
									
								}
							}

							$saldoafavor = 0;

				        	foreach ($conceptos as $concepto) {

				        		if (!empty($concepto)) {

				        			if ($concepto['total'] < 0 && $concepto['concepto'] == 'Saldo a Favor') {
				        				$saldoafavor += ($concepto['total'] * (-1));
				        			}

				        			$item = new FacturaItem;
						        	$item->concepto = $concepto['concepto'];
					                $item->cantidad = $concepto['cantidad'];
					                $item->valor_unidad = $concepto['valor_unidad'];

					                if (isset($concepto['unidad_medida'])) {
					                	$item->unidad_medida = $concepto['unidad_medida'];
					                }
					                
					                $item->iva = $concepto['iva'];
					                $item->valor_iva = $concepto['valor_iva'];
					                $item->valor_total = $concepto['total'];
					                $item->factura_id = $factura->FacturaId;

					                if (!$item->save()) {
					                	DB::rollBack();
				                    }
				        		}					        	
				            }

				            $factura_actualizar = Facturacion::find($factura->FacturaId);
				            $factura_actualizar->saldo_favor = $saldoafavor;

				            if (!$factura_actualizar->save()) {
				            	DB::rollBack();
				            }

				        }else{
				        	DB::rollBack();
				        }
			    	}else{
			    		continue;
			    	}
			    }else{
					continue;
				}        
			}
		});

		
	}
}