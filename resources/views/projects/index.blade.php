<!-- * Extension du layout parent * -->
@extends('partials.base-layout')


<!-- * Contenu * -->

@section('layout-content')

<div class="projects-list">
    <div class="categories-list">
        <ul>
            @foreach($categories as $category)
            <li><a href="{{route('projects.index', ['category' => $category->slug])}}" class="categories-list__link button {{request()->category == $category->slug ? 'categories-list__link--is-active' : '' }} is-rounded">{{$category->name}}</a></li>
            @endforeach
        </ul>
    </div>

    @forelse($projects as $project)

    <div class="project-card card">
        <a class="project-card__authors-profile-link" href="{{route('profiles.indexPublishedProjects', ['user' => $project->user])}}"w>
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
    @empty
    <p class="projects-list__text">Il n'y a aucun projet pour le moment. N'hésitez pas à revenir de temps en temps.</p>
    @endforelse


</div>
@endsection