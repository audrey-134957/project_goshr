@extends('partials.admin-base-layout')

@section('admin-title', "Modifier le compte administrateur n° $adminUser->id")

@section('admin-header-subtitle', "Modifier le compte administrateur n° $adminUser->id")

@section('layout-content')

<div class="user-profile-edition">

    <form action="{{route('admin.updateAdmin', [ 'adminId' => auth()->user()->id,'adminUser' => $adminUser->id]) }}" method="POST" enctype="multipart/form-data">

        @method('PATCH')
        @csrf

        <div class="user-profile-edition__fields fields">

            <!-- * Champs avatar * -->
            <div class="user-profile-edition__field field">
                <label for="avatar" class="user-profile-edition__label user-profile-edition__label--title label">Photo de profil</label>
                <label class="user-profile-edition__label user-profile-edition__label--image user-profile-edition__label--avatar box__label--image label" for="avatar" style="background-image: linear-gradient(rgba(94, 94, 94, 0.341), rgba(94, 94, 94, 0.341)), url('{{$adminUser->getImage($adminUser)}}');">
                </label>
                <div class="user-profile-edition__box-input control">
                    <input class="user-profile-edition__input user-profile-edition__input--file user-profile-edition__input--avatar box__input--file input" type="file" id="avatar" name="avatar">
                </div>
                @error('avatar')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="d-flex">
                <div class="d-flex__left">
                    <!-- * Champs email * -->
                    <div class="user-profile-edition__field user-profile-edition__email-field field">
                        <label class="user-profile-edition__label user-profile-edition__label--email label">Email</label>
                        <div class="control">
                            <input class="user-profile-edition__input user-profile-edition__input--email @error('email') is-danger @enderror is-rounded input" type="email" name="email" value="{{$adminUser->email}}">
                        </div>
                        @error('email')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- * Champs nom * -->
                    <div class="user-profile-edition__field user-profile-edition__name-field field">
                        <label class="user-profile-edition__label user-profile-edition__label--name label">Nom</label>
                        <div class="control">
                            <input class="user-profile-edition__input user-profile-edition__input--name @error('name') is-danger @enderror is-rounded input" type="text" name="name" value="{{$adminUser->name}}">
                        </div>
                        @error('name')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- * Champs prénom * -->
                    <div class="user-profile-edition__field user-profile-edition__firstname-field field">
                        <label class="user-profile-edition__label user-profile-edition__label--firstname label">Prénom</label>
                        <div class="control">
                            <input class="user-profile-edition__input user-profile-edition__input--firstname @error('firstname') is-danger @enderror is-rounded input" type="text" name="firstname" value="{{$adminUser->firstname}}">
                        </div>
                        @error('firstname')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="d-flex__right">

                    <!-- * Champs nouveau mot de passe * -->
                    <div class="user-profile-edition__field user-profile-edition__password-field user-profile-edition__password-field--new box__password--new field">
                        <label class="user-profile-edition__label user-profile-edition__label--password box__password--new label">Nouveau mot de passe</label>
                        <div class="control">
                            <input class="user-profile-edition__input user-profile-edition__input--password box__password--new @error('password_new') is-danger @enderror is-rounded input" type="password" name="password_new">
                        </div>
                        @error('password_new')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- * Champs confirmation du nouveau mot de passe * -->
                    <div class="user-profile-edition__field user-profile-edition__password-field user-profile-edition__password-field--new-confirmation box__password--new-confirmation field">
                        <label class="user-profile-edition__label user-profile-edition__label--password box__password--new-confirmation label">Confirmation du nouveau mot de passe</label>
                        <div class="control">
                            <input class="user-profile-edition__input user-profile-edition__input--password box__password--new-confirmation @error('password_new_confirmation') is-danger @enderror is-rounded input" type="password" name="password_new_confirmation">
                        </div>
                        @error('password_new_confirmation')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="user-profile-edition__field user-profile-edition__role-select field">
            <label class="user-profile-edition__label user-profile-edition__label--email label">Rôle</label>
                <div class="select @error ('admin_role') is-danger @enderror is-rounded">
                    <select name="admin_role">
                        @foreach($roles as $role)
                        <option value="{{$role->id}}" {{$adminUser->role_id === $role->id ? 'selected' : ''}}>{{$role->name}}</option>
                        @endforeach
                    </select>
                </div>
                @error('admin_role')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <button class="user-profile-edition__button user-profile-edition__button--update  button is-rounded" type="submit">modifier</button>
    </form>
    <div class="user-profile-edition__buttons-box">
        <button class="user-profile-edition__button button modal-button is-rounded showUserDeletingModal">supprimer le compte</button>
        @include('admins.partials.modals.deletion.admins.admin-modal')

        <button class="user-profile-edition__button button modal-button is-rounded showBanModal">bannir cette utilisateur</button>
        @include('admins.partials.modals.bans.admins.admin-modal')

    </div>
</div>

@endsection