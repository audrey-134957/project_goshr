<!-- * Extension du layout parent * -->
@extends('partials.base-layout')

<!-- * Contenu * -->

@section('layout-content')
<header class="profile-header box">
    <div class="profile-header__presentation">
        <figure class="profile-header__image image is-128x128">
            <img class="is-rounded" src="{{$user->getImage($user)}}" alt="Placeholder image">
        </figure>
        <div class="profile-header__member-infos">
            <span class="profile-header__member-username">{{$user->username}}</span>
            <span class="profile-header__existence">membre depuis le {{$user->getUserCreationDate()}}</span>
        </div>
    </div>
    <div class="profile-header__biography-box">
        <span class="profile-header__title title">Description</span>
        <p class="profile-header__biography">
            @if(empty($user->profile->biography))
            Aucune description en vue!
            @else
            {!! nl2br(e($user->profile->biography)) !!}
            @endif
        </p>
    </div>
</header>

<div class="user-profile-edition">
    <form action="{{route('profiles.update', ['user' => $user]) }}" method="POST" enctype="multipart/form-data">
        @method('PATCH')
        @csrf

        <div class="user-profile-edition__fields fields">


            <div class="user-profile-edition__field field">
                <label for="avatar" class="user-profile-edition__label user-profile-edition__label--title label">Photo de profil</label>
                <label class="user-profile-edition__label user-profile-edition__label--image user-profile-edition__label--avatar box__label--image label" for="avatar" style="background-image: linear-gradient(rgba(94, 94, 94, 0.341), rgba(94, 94, 94, 0.341)), url('{{$user->getImage($user)}}');">
                </label>
                <div class="user-profile-edition__box-input control">
                    <input class="user-profile-edition__input user-profile-edition__input--file user-profile-edition__input--avatar box__input--file input" type="file" id="avatar" name="avatar">
                </div>
                @error('avatar')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>



            <div class="user-profile-edition__field box__bio field">
                <label class="label">Biographie</label>
                <div class="control">
                    <textarea class="user-profile-edition__textarea textarea @error('biography') is-danger @enderror" rows="8" name="biography" value="{{old('biography')}}">@if(!empty($user->profile->biography)){!! nl2br(e($user->profile->biography)) !!}@endif</textarea>
                </div>
                @error('biography')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>
            <div class="d-flex">
                <div class="d-flex__left">

                    <div class="user-profile-edition__field user-profile-edition__email-field field">
                        <label class="label">Email</label>
                        <div class="control">
                            <input class="@error('email') is-danger @enderror is-rounded input" type="email" name="email" value="{{$user->email}}">
                        </div>
                        @error('email')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="user-profile-edition__field user-profile-edition__name-field field">
                        <label class="user-profile-edition__label user-profile-edition__label--name label">Nom</label>
                        <div class="control">
                            <input class="@error('name') is-danger @enderror is-rounded input" type="text" name="name" value="{{$user->name ?? old('name')}}">
                        </div>
                        @error('name')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="user-profile-edition__field user-profile-edition__firstname-field field">
                        <label class="user-profile-edition__label user-profile-edition__label--firstname label">Prénom</label>
                        <div class="control">
                            <input class="@error('firstname') is-danger @enderror is-rounded input" type="text" name="firstname" value="{{$user->firstname ?? old('firstname')}}">
                        </div>
                        @error('firstname')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="d-flex__right">

                    <div class="user-profile-edition__field user-profile-edition__password-field field">
                        <label class="label">Mot de passe</label>
                        <div class="control">
                            <input class="@error('password') is-danger @enderror is-rounded input" type="password" name="password">
                        </div>
                        @error('password')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="user-profile-edition__field user-profile-edition__password-field user-profile-edition__password-field--new field">
                        <label class="label">Nouveau mot de passe</label>
                        <div class="control">
                            <input class="@error('password_new') is-danger @enderror is-rounded input" type="password" name="password_new">
                        </div>
                        @error('password_new')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="user-profile-edition__field user-profile-edition__password-field user-profile-edition__password-field--new-confirmation field">
                        <label class="label">Confirmation du nouveau mot de passe</label>
                        <div class="control">
                            <input class="@error('password_new_confirmation') is-danger @enderror is-rounded input" type="password" name="password_new_confirmation">
                        </div>
                        @error('password_new_confirmation')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        <button class="user-profile-edition__button user-profile-edition__button--update  button is-rounded">modifier</button>
    </form>

    <span class="user-profile-edition__modal-activation modal-button">Supprimer mon compte</span>
    @include('partials.modals.deletion.profile.modal')
</div>

@endsection