@component('mail::message')
#Estimado Vladimir


Se informa que el cliente identificado con cédula **{{$cedula}}**, corresponde a una **{{$novedad}}** ha sido **{{$estado}}** por motivos de **{{$observaciones}}**.

@if($novedad == 'VENTA')

se requiere la siguiente documentación:

Foto del recibo público<br>
Foto de Cédula Cara 1<br>
Foto de Cédula Cara 2<br>
Foto Panorámica de la vivienda<br>
Foto de la vivienda con la dirección<br>

Por favor responder éste correo con la respectiva documentación requerida.

@endif

@component('mail::button', ['url' => 'http://encuestas.amigored.co'])
Plataforma de Encuestas
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent