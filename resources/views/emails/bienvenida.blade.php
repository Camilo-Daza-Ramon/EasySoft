@component('mail::message')
#Bienvenido(a) **{{$nombre_remitente}}**

A continuación se apreciarán los datos de ingreso de su cuenta SISCO - Intranet: <br>

<b>URL:</b> http://localhost:8080<br>
<b>Usuario:</b> {{$usuario}}<br>
<b>Contraseña:</b> {{$contrasena}}<br>

Recuerde tener esta información en un lugar seguro. Puede tambien cambiar la contraseña en la configuración de su perfil.
Gracias,<br>
@endcomponent