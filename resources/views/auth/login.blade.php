<!-- * Extension du layout parent * -->
@extends('partials.base-layout')


<!-- * Contenu * -->

@section('layout-content')

<div class="auth-box box">
    <span class="auth-box__title title">Connexion</span>

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

            <button class="auth-box__button auth-box__button--is-submit button is-rounded" type="submit">connexion</button>
        </form>
        <hr>
        <div class="auth-box__box-footer">
            <small class="auth-box__related-text"><a href="{{route('forgotPwd.create')}}" class="auth-box__related-link">J'ai oublié mon mot de passe</a></small>
            <small class="auth-box__related-text">Pas encore de compte? <a href="{{route('register.create')}}" class="auth-box__related-link">Inscription</a></small>
        </div>
    </div>
</div>
@endsection