@component('mail::message')

# {{$user->username}},

Tu as demandé un lien de réinitalisation du mot de passe. Nous t'invitons à cliquer sur le lien ci-dessous te permettra d'en générer un nouveau.


@component('mail::button', ['url' => route('resetPassword.edit',['user' => $user->username, 'token_reset' => $user->token_reset])])
    Réinitialiser mon mot de passe
@endcomponent



À bientôt sur Goshr !

@endcomponent