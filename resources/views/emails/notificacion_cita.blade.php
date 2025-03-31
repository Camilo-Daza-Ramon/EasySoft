@component('mail::message')
#Estimado(a) **{{$paciente}}**

Tu cita ha sido programada correctamente <br>

<b>Fecha de a cita:</b> {{$fecha_cita}}<br>
<b>Hora de la cita:</b> {{$hora_cita}}<br>
<b>Especialidad:</b> {{$especialidad}}<br>
<b>Médico:</b> {{$medico}}<br>
<b>Sede:</b> {{$sede}}<br>
<b>Dirección:</b> {{$direccion}} <br>


@component('mail::button', ['url' => $mapa])
¿Cómo llegar?
@endcomponent


Recuerda llegar entre 15 y 20 minutos antes y presentarte en la ventanilla para autorizar su cita. Te informamos que para la modificación o cancelación de la cita, debes hacerlo un (1) día antes de la fecha de la cita. En caso de llegar tarde, la cita se cancelará y deberá solicitar otra nuevamente.
Gracias.<br>


Nota: Este mensaje ha sido generado automaticamente. Por favor no lo responda.

@endcomponent