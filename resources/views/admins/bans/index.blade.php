@extends('partials.admin-base-layout')

@section('admin-title', 'bannissements')

@section('admin-header-subtitle', 'bannissements')

@section('layout-content')

<div class="search-box">

    <form action="{{route('admin.search', ['adminId' => auth()->user()->id])}}" method="GET" class="search-box__form">
        <input type="search" class="search-box__search-input input is-rounded" name="q" placeholder="Rechercher...">
        <button class="search-box__button search-box__button--submit button is-rounded">
            <i class="fa fa-search" aria-hidden="true"></i>
        </button>
    </form>
</div>

<section class="section">
    <div class="bans-list">
    <span class="bans-list__bans-number">{{$bans->count() .' '. $name}}</span>

        @foreach($bans as $ban)
        <div class="ban-card card">
            <div class="ban-card__card-content card-content">

                <div class="ban-card__pseudo-box ban-card__info">
                    <span class="ban-card__title">email :</span>
                    <span class="section__user-info section__user-username">{{$ban->banned_user_email}}</span>
                </div>

                <div class="ban-card__email-box ban-card__info">
                    <span class="ban-card__title">adresse ip:</span>
                    <span>{{$ban->ip}}</span>
                </div>

            </div>
            <div class="ban-card__card-footer card-footer">
                <button class="ban-card__button modal-button is-primary is-light button is-rounded">retirer du ban</button>
                @include('admins.partials.modals.deletion.ban.admin-modal')
            </div>
        </div>

        @endforeach
    </div>
</section>
@endsection