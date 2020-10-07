@extends('partials.admin-base-layout')

@section('admin-title', 'administrateurs')

@section('admin-header-subtitle', 'administrateurs')

@section('layout-content')
<div class="search-box">

    <form action="{{route('admin.searchAdmins', ['adminId' => auth()->user()->id])}}" method="GET" class="search-box__form">
        <input type="text" class="search-box__search-input input is-rounded" name="q" placeholder="Rechercher..." value="{{request()->q ?? ''}}">
        <button class="search-box__button search-box__button--submit button is-rounded">
            <i class="fa fa-search" aria-hidden="true"></i>
        </button>
    </form>
</div>
<section class="section">
    <div class="users-list">
        <span class="users-list__users-number">{{$users->count(). ' '.$name}}</span>
        @foreach($users as $user)
        <div class="user-card card">
            <div class="user-card__card-header card-header">
                <figure class="user-card__image image is-48x48">
                    <img class="is-rounded" src="{{$user->getImage($user)}}" alt="Placeholder image">
                </figure>
            </div>
            <div class="user-card__card-content card-content">

                @if($user->name && $user->firstname !== NULL)
                <div class="user-card__complete-name-box">
                    @php
                    $completeName = $user->firstname. ' '.$user->name;
                    @endphp
                    <span class="user-card__complete-name">{{$completeName}}</span>
                </div>
                @endif

                <div class="user-card__pseudo-box user-card__info">
                    <span class="user-card__title title">pseudonyme</span>
                    <span class="section__user-info section__user-username">{{$user->username}}</span>
                </div>

                <div class="user-card__email-box user-card__info">
                    <span class="user-card__title title">email</span>
                    <span>{{$user->email}}</span>
                </div>

                <div class="user-card__existance-box user-card__info">
                    <span class="user-card__title title">ancienneté</span>
                    <span>membre depuis le 10 jan 2000</span>
                </div>

                <div class="user-card__status-box user-card__info">
                    <span class="user-card__title title">status</span>
                    <span>{{$user->level->name}}</span>
                </div>
            </div>
            <div class="user-card__card-footer card-footer">
                <a href="{{route('admin.editUser', ['adminId' => auth()->user()->id, 'user' => $user->username])}}" class="user-card__button user-card__button--edit button is-rounded is-info is-outlined"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                <button class="user-card__button user-card__button--delete modal-button button is-rounded is-link is-outlined" type="button"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                @include('admins.partials.modals.deletion.users.admin-modal')

                <button class="user-card__button user-card__button--delete modal-button button is-danger is-rounded is-outlined" type="button"><i class="fa fa-ban" aria-hidden="true"></i></button>
                @include('admins.partials.modals.bans.users.admin-modal')
            </div>
        </div>
        @endforeach
    </div>
</section>
@endsection