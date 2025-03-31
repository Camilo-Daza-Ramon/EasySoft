@component('mail::message')
#Estimado(a) **{{$nombre_remitente}}**

Se requiere de su valiosa gestión para tramitar y responder a este correo la siguiente solicitud de documentación: <br>

<b>Número de Constancia: </b> {{$licitacion}} <br>
<b>Nombre Archivo: </b> {{$nombre_documento}} <br>
<b>Observaciones: </b> {{$observaciones}} <br>

Gracias,<br>
@endcomponent