<!-- * Extension du layout parent * -->
@extends('partials.base-layout')


<!-- * Contenu * -->

@section('layout-content')
<div class="auth-box box">
    <span class="auth-box__title title">Mot de passe oublié ?</span>
    <p class="auth-box__text">Pas de panique ! vous pouvez demander un lien de réinitialisation de mot de passe en renseignant votre email dans le champs ci-dessous.</p>

    <div class="auth-box__box-content">
        <form action="" method="POST">
            @csrf

            <!-- * Champs email * -->
            <div class="auth-box__field field">
                <label class="auth-box__label label">Email</label>
                <div class="control">
                    <input class="auth-box__input input @error('email') is-danger @enderror is-rounded" type="email" name="email" value="{{old('email')}}">
                </div>
                @error('email')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>

            <button class="auth-box__button auth-box__button--is-submit button is-rounded" type="submit">Réinitaliser mon mot de passe</button>
        </form>
    </div>
</div>
@endsection