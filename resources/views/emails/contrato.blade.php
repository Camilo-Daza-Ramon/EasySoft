@component('mail::message')
#Hola, **{{$data['nombre_suscriptor']}}**

Somos Amigo Red, la compañia de Internet Fijo que contrató hace unos días. A continuación le informamos sus servicios contratados:<br>


@component('mail::table')
| Servicios       | Descripcion         | Cantidad  | Valor     |
| --------------- |:-------------------:| ---------:| ---------:|
@foreach($data['servicios'] as $servicio)
{{'|'.$servicio->tipo_servicio.'|'}}{{$servicio->descripcion.'|'}}{{number_format($servicio->cantidad,0, ',','.').' '.$servicio->unidad_medida.'|'}}{{'$'. number_format($servicio->valor, 0, ',','.') .'|'}}
@endforeach
@endcomponent


<b>Total a Pagar Mensualmente:</b> ${{$data['valor_pagar']}}<br>
<b>Vigencia:</b> {{$data['vigencia']}} Meses<br>
<b>Tipo de Cobro:</b> {{$data['tipo_cobro']}}<br>
<b>Estrato:</b> {{$data['estrato']}}<br>


Hemos adjuntado el contrato y sus respectivas actas las cuales aceptó al momento de la venta del servicio.
Por favor leerlo y cualquier duda estaremos ahí para aclararla.<br>

Recuerde guardar esta información en un lugar seguro. Podrá solicitarla nuevamente en cualquier momento por medio de nuestros canales de contacto.<br>

Gracias,<br>
@endcomponent