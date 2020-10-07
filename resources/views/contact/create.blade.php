@extends('partials.base-layout')


@section('layout-content')
<div class="contact-box box">
    <div class="contact-box__header">
        <h1 class="contact-box__title title">Contactez-nous !</h1>
        <p class="contact-box__subtitle">Une question, un problème ou une suggestion à vous transmettre ? <br> Tu peux remplir le formulaire ci-dessous ou nous contacter via <a href="mailto:contact@goshr.fr">contact@goshr.fr</a>.</p>
    </div>

    <form class="contact-box__form" action="" method="POST">
        @csrf
        <!-- * Champs om et prénom de l'expéditaire * -->
        <div class="contact-box__field field">
            <label class="contact-box__label label">Ton nom et prénom</label>
            <div class="control">
                <input class="contact-box__input input @error('complete_name') is-danger @enderror is-rounded" type="text" name="complete_name">
            </div>
            @error('complete_name')
            <p class="help is-danger">{{$message}}</p>
            @enderror
        </div>
        <!-- * Champs email * -->
        <div class="contact-box__field field">
            <label class="contact-box__label label">Ton email</label>
            <div class="control">
                <input class="contact-box__input input @error('email') is-danger @enderror is-rounded" type="email" name="email">
            </div>
            @error('email')
            <p class="help is-danger">{{$message}}</p>
            @enderror
        </div>
        <!-- * Champs objet * -->
        <div class="contact-box__field field">
            <label class="contact-box__label label">L'objet de ton message</label>
            <div class="control">
                <input class="contact-box__input input @error('subject') is-danger @enderror is-rounded" type="text" name="subject">
            </div>

            @error('subject')
            <p class="help is-danger">{{$message}}</p>
            @enderror
        </div>
        <!-- * Champs message* -->
        <div class="contact-box__field field">
            <label class="contact-box__label label">Ton message</label>
            <div class="control">
                <textarea class="contact-box__textarea textarea @error('message') is-danger @enderror" name="message" placeholder="Quelque soit le problème nous serions ravis de vous aider!"></textarea>
            </div>
            @error('message')
            <p class="help is-danger">{{$message}}</p>
            @enderror
        </div>

        <button type="submit" class="contact-box__button contact-box__button--submit button is-rounded">
            Envoyer !
        </button>
    </form>
</div>
@endsection