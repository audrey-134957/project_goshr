<!-- * Extension du layout parent * -->
@extends('partials.base-layout')


<!-- * Contenu * -->

@section('layout-content')
<div class="auth-box box">
    <span class="auth-box__title title">Changement de votre mot de passe</span>

    <div class="auth-box__box-content">
        <form action="" method="POST">
            @method('PATCH')
            @csrf

            <!-- * Champs nouveau mot de passe * -->
            <div class="auth-box__field field">
                <label class="auth-box__label label">Nouveau mot de passe</label>
                <div class="control">
                    <input class="auth-box__input input @error('password_confirmation') is-danger @enderror is-rounded" type="password" name="password">
                </div>
                @error('password')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>

            <!-- * Champs confirmation du mot de passe * -->
            <div class="auth-box__field field">
                <label class="auth-box__label label">Confirmation du nouveau mot de passe</label>
                <div class="control">
                    <input class="auth-box__input input @error('password_confirmation') is-danger @enderror is-rounded" type="password" name="password_confirmation">
                </div>
                @error('password_confirmation')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>
            <button class="auth-box__button auth-box__button--is-submit button is-rounded" type="submit">Réinitialiser mon mot de passe</button>
        </form>
    </div>
</div>

@endsection