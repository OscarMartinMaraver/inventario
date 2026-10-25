@component('mail::message')
# {{ $form['document'] }}

Se ha generado un nuevo documento y se ha enviado a su correo electronico

Gracias, <br>
{{ config('app.name') }}
@endcomponent