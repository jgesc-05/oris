@component('mail::message')
# Hola {{ $appointment->paciente->nombres ?? 'Paciente' }},

Te recordamos que tu cita con **{{ $appointment->medico->nombres ?? 'tu médico' }}**
es mañana, el **{{ $appointment->fecha_hora_inicio->format('d/m/Y H:i') }}**.

Por favor, llega 10 minutos antes de la hora programada.


Cordialmente,<br>
**El equipo de VitalCare IPS**
@endcomponent

