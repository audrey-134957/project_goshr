@component('mail::message')

# {{$admin->firstname}}


Te voici à présent membre administrateur ! 

Confirme ton adresse mail valider ton compte en cliquant sous le lien qui t'est fourni juste en dessous.


@component('mail::button', ['url' => route('validation.validateAdmin', ['admin' => $admin->id, 'token' => $admin->token])])
confirmer mon compte
@endcomponent
   
L'équipe Goshr.

@endcomponent