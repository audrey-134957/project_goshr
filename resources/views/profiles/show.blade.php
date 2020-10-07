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

    <!--seule le propriétaire du profil pourra voir un bouton de modification de profil-->
    @can('update', $user->profile)
    <a href="{{route('profiles.edit', ['user' => $user->username, 'token' => $user->token_account])}}" class="profile-header__button profile-header__button--edit button is-rounded">modifier</a>
    @endcan
</header>
<nav class="profile-navbar">
    <ul class="profile-navbar__list-items">
        <li class="profile-navbar__item {{request()->route()->named('profiles.indexPublishedProjects') ? 'profile-navbar__item--is-active' : ''}}"><a href="{{route('profiles.indexPublishedProjects',  ['user'=> $user->username])}}" class="navigation__link">@yield('profile-navbar-link-1','Mes projets')</a></li>
        @auth
        <li class="profile-navbar__item {{request()->route()->named('profiles.indexDraftedProjects') ? 'profile-navbar__item--is-active' : ''}}"><a href="{{route('profiles.indexDraftedProjects',  ['user'=> $user->username])}}" class="navigation__link">@yield('profile-navbar-link-2','Mes brouillons')</a></li>
        @endauth
    </ul>
</nav>

<div class="projects-list projects-list--profile">
    @forelse($projects as $project)
    @php
    if(request()->route()->named('profiles.indexDraftedProjects')){
    $projectRoute = route('projects.draft', [$project, 'slug' => $project->slug, 'token' => $user->bank_of_token->token_project_draft]);

    }else{
    $projectRoute = route('projects.show', [$project, 'slug' => $project->slug]);
    }
    @endphp
    <div class="project-card card">
        <a class="project-card__authors-profile-link" href="{{route('profiles.indexPublishedProjects', ['user' => $project->user])}}" w>
            <div class="project-card__media-left">
                <figure class="project-card__image image is-40x40">
                    <img class="is-rounded" src="{{$project->user->getImage($project->user)}}" alt="Placeholder image">
                </figure>
                <small class="project-card__author-username">{{$project->user->username}}</small>
            </div>
        </a>
        <div class="project-card__action-buttons-box">
            @auth
            @canany(['update', 'delete'], $project)
            @php
            if(request()->route()->named('profiles.indexDraftedProjects')){
            $routeLink = route('projects.draft', [$project, 'slug' => $project->slug, 'token' => $user->bank_of_token->token_project_draft]);
            }else{
            $routeLink = route('projects.edit', [$project, 'slug'=> $project->slug, 'token' => $project->user->bank_of_token->token_project]);
            }
            @endphp
            <a href="{{$routeLink}}" class="project-card__edit-button button is-rounded is-warning is-outlined"><i class="fa fa-pencil" aria-hidden="true"></i></a>

            <button class="project-card__delete-button button is-rounded is-danger is-outlined modal-button" type="button"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
            @include('partials.modals.deletion.project.modal')
            @endcanany
            @endauth
        </div>
        <a href="{{$projectRoute}}">
            <figure class="project-card__thumbnail-figure image is-4by3">
                <img class="project-card__thumbnail" src="{{$project->getThumbnail($project)}}" alt="Placeholder image">
            </figure>

            <div class="project-card__card-content card-content">

                <div class="project-card__infos">
                    <div class="project-card__materials-box">
                        <span class="project-card__material-number">{{$project->materials->count(). ' mat.'}}</span>
                    </div>

                    <div class="project-card__difficulty-level-box">
                        <span class="project-card__difficulty-level project-card__difficulty-level--{{$project->difficulty_level->en_name}}">{{$project->difficulty_level->name}}</span>
                    </div>

                    <div class="project-card__duration-box">
                        <i class="project-card__icon project-card__icon--clock fa fa-clock-o" aria-hidden="true"></i>
                        {{$project->getDuration()}}
                    </div>

                    <div class="project-card__budget-box">
                        <i class="project-card__icon project-card__icon--material fa fa-shopping-basket" aria-hidden="true"></i>
                        <span class="project-card__budget">{{$project->budget}}&euro;</span>
                    </div>
                </div>

                <h4 class="project-card__title title">
                    {{$project->title}}
                </h4>

                <div class="project-card__content content">
                    {!! Str::words($project->content, 20) !!}
                </div>
            </div>
        </a>
    </div>
    @empty
    <p class="projects-list__text">Il n'y a aucun projet pour le moment.</p>
    @endforelse
</div>
@endsection