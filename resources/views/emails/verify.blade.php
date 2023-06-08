@component('mail::message')
# Vérification de votre adresse email

Merci de vous être inscrit sur notre site. Veuillez cliquer sur le lien ci-dessous pour vérifier votre adresse email :

@component('mail::button', ['url' => $verificationUrl])
Vérifier mon adresse email
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent