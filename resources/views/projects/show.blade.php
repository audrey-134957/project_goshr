<!-- * Extension du layout parent * -->
@extends('partials.base-layout')


<!-- * Contenu * -->

@section('layout-content')

<article class="project-article-box">
    <div class="project-article-box__anchors-buttons">
        <a href="#projet" class="project-article-box__anchor-button button is-rounded"><i class="fa fa-file-text-o" aria-hidden="true"></i></a>
        <a href="#q-a" class="project-article-box__anchor-button button is-rounded"><i class="fa fa-question" aria-hidden="true"></i></a>
        <a href="#commentaires" class="project-article-box__anchor-button button is-rounded"><i class="fa fa-comments-o" aria-hidden="true"></i></a>
        <a href="#suggestion" class="project-article-box__anchor-button button is-rounded"><i class="fa fa-th-large" aria-hidden="true"></i></a>
    </div>

    <div class="project-article box" id="projet">

        <div class="project-article__header">
            <div class="project-article__float-right">
                @auth
                @canany(['update', 'delete'], $project)
                <a href="{{route('projects.edit', [$project, $slug, 'token' => $project->user->bank_of_token->token_project])}}" class="project-article__button project-article__link-button button is-rounded is-warning is-outlined"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                <button class="project-article__button button is-rounded is-danger is-outlined modal-button" type="button"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                @include('partials.modals.deletion.project.modal')
                @endcanany

                @can('doReport', $project)
                @php

                // je vérifie si mon topic a déjà été reporté par l'utilisateur connecté;

                $reportedProject = $project->reports()->where('user_id', auth()->user()->id)->get();
                @endphp
                @if($reportedProject->count() === 0)
                <button class="project-article__button project-article__button--report-modal button is-rounded modal-button is-white">
                    <i class="project-article__icon project-article__icon--report fa fa-ban" aria-hidden="true"></i>
                </button>


                @include('partials.modals.reports.project.modal')

                @else
                <span class="project-article__tag tag is-medium is-right">
                    <i class="project-article__tag-icon fa fa-check" aria-hidden="true"></i>
                    signalé
                </span>
                @endif
                @endcannot
                @endauth

            </div>
        </div>

        <h1 class="project-article__title project-article__title--project title">{{$project->title}}</h1>

        <div class="project-article__infos">
            <span class="project-article__category category project__tag">{{$project->category->name}}</span>

            <span class="project-article__difficulty-level project-article__difficulty-level--{{$project->difficulty_level->en_name}}">{{$project->difficulty_level->name}}</span>

            <span class="project-article__duration"><i class="project-article__icon project-article__icon--clock fa fa-clock-o" aria-hidden="true"></i>{{$project->getDuration()}}</span>

            <span class="project-article__materials"><i class="project-article__icon project-article__icon--material fa fa-shopping-basket" aria-hidden="true"></i>{{$project->materials->count(). ' mat.'}}</span>
        </div>

        <div class="project-article__media media">
            <a href="{{route('profiles.indexPublishedProjects', ['user' => $project->user->username])}}">
                <div class="project-article__media-left media-left">
                    <figure class="project-article__image image is-40x40">
                        <img class="is-rounded" src="{{$project->user->getImage($project->user)}}" alt="Placeholder image">
                    </figure>
                    <span class="project-article__author-username">{{$project->user->username}}</span>
                </div>
            </a>
        </div>

        <div class="project-article__content">
            <div class="project-article__materials-box">
                <span class="project-article__materials-box-title project-article__title--materials tag is-medium">matériels</span>
                <div class="project-article__materials-list">
                    <ul>
                        @foreach($project->materials as $material)
                        <li class="project-article__item project-article__item--materials">{{$material->name}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="project-article__main">
                {!! $project->content !!}
            </div>
        </div>
    </div>

    @include('layouts.topics-section')

    @include('layouts.comments-section')

    @unless($projects->count() === 0)
    <div class="suggestion-projects-list projects-list" id="suggestion">
        <h2 class="projects-list__title title">Suggestion de projets</h2>

        @foreach($projects as $project)
        <div class="project-card card">
            <a class="project-card__authors-profile-link" href="{{route('profiles.indexPublishedProjects', ['user' => $project->user])}}" w>
                <div class="project-card__media-left">
                    <figure class="project-card__image image is-40x40">
                        <img class="is-rounded" src="{{$project->user->getImage($project->user)}}" alt="Placeholder image">
                    </figure>
                    <small class="project-card__author-username">{{$project->user->username}}</small>
                </div>
            </a>
            <a href="{{route('projects.show', ['project' => $project, 'slug' => $project->slug])}}">
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

                    <div class="project-card__content content">{!! Str::words($project->content, 20) !!}</div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    @endunless
</article>


@endsection