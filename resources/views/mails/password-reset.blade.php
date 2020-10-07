@component('mail::message')

# {{$user->username}},

Ton mot de passe a été réinitialisé. Si tu n'es pas à l'origine de ce changement, nous t'invitons à contacter le support.

A très bientôt!<br>


{{config('app.name')}}

@endcomponent