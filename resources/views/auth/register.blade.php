<!-- * Extension du layout parent * -->
@extends('partials.base-layout')


<!-- * Contenu * -->

@section('layout-content')

<div class="auth-box box">
    <span class="auth-box__title title">Inscription</span>

    <div class="auth-box__box-content">
        <form action="" method="POST">
            @csrf

            <!-- * Champs pseudo * -->
            <div class="auth-box__field field">
                <label class="auth-box__label label">Pseudo</label>
                <div class="control">
                    <input class="auth-box__input input @error('username') is-danger @enderror is-rounded" type="text" name="username">
                </div>
                @error('username')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>

            <!-- * Champs email * -->
            <div class="auth-box__field field">
                <label class="auth-box__label label">Email</label>
                <div class="control">
                    <input class="auth-box__input input @error('email') is-danger @enderror is-rounded" type="email" name="email">
                </div>
                @error('email')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>

            <!-- * Champs mot de passe * -->
            <div class="auth-box__field field">
                <label class="auth-box__label label">Mot de passe</label>
                <div class="control">
                    <input class="auth-box__input input @error('password') is-danger @enderror is-rounded" type="password" name="password">
                </div>
                @error('password')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>

            <!-- * Champs confirmation du mot de passe * -->
            <div class="auth-box__field field">
                <label class="auth-box__label label">Confirmation du mot de passe</label>
                <div class="control">
                    <input class="auth-box__input input @error('password_confirmation') is-danger @enderror is-rounded" type="password" name="password_confirmation">
                </div>
                @error('password_confirmation')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>

            <button class="auth-box__button auth-box__button--is-submit button is-rounded" type="submit">inscription</button>
        </form>
        <hr>
        <div class="auth-box__box-footer">
            <small>Déjà parmi nous? <a href="{{route('login.create')}}" class="auth-box__related-link">Connexion</a></small>
        </div>
    </div>
</div>
@endsection