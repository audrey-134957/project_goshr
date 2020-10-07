@component('mail::message')

# Nous te souhaitons la bienvenue dans la communauté Goshr !



Nous sommes ravie de te compter parmis nos membre !

Nous t'invitons désormais à confirmer ton adresse mail valider ton inscription en cliquant sous le lien qui t'est founit juste en dessous.


@component('mail::button', ['url' => route('validation.validateUser', ['user' => $user, 'token' => $user->token])])
Je confirme mon adresse mail!
@endcomponent
   

@endcomponent