@extends('partials.admin-base-layout')

@section('admin-title', 'projets')

@section('admin-header-subtitle', 'projets')

@section('layout-content')
<div class="search-box">

    <form action="{{route('admin.searchProjects', ['adminId' => auth()->user()->id])}}" method="GET" class="search-box__form">
        <input type="search" class="search-box__search-input input is-rounded" name="q" value="{{request()->q ?? '' }}" placeholder="Rechercher...">
        <button class="search-box__button search-box__button--submit button is-rounded">
            <i class="fa fa-search" aria-hidden="true"></i>
        </button>
    </form>
</div>


<section class="section">
    <form id="selection-form" action="{{route('admin.deleteProjectsSelection', ['adminId' => auth()->user()->id] )}}" method="POST">
        @csrf
        @method('DELETE')
        <div class="projects-list">


            <div class="projects-list__header">
                <span class="projects-list__projects-number">{{$projects->count() .' '. $text}}</span>
                <button class="projects-list__button projects-list__button--delete-selection button is-rounded" type="submit" name="submit" value="selection">
                    supprimer la sélection
                </button>
            </div>


            @foreach($projects as $project)
            <div class="project-card card">
                <div class="project-card__checkbox-box">
                    <button class="project-card__button--delete button is-rounded is-danger is-outlined modal-button" type="button"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                    <label class="project-card__checkbox-label checkbox">
                        <input class="project-card__checkbox" name="checkbox[]" value="{{$project->id}}" type="checkbox">
                    </label>
                </div>
                <a class="project-card__authors-profile-link">
                    <div class="project-card__media-left">
                        <figure class="project-card__image image is-40x40">
                            <img class="is-rounded" src="{{$project->user->getImage($project->user)}}" alt="Placeholder image">
                        </figure>
                        <small class="project-card__author-username">{{$project->user->username}}</small>
                    </div>
                </a>
                <a href="{{route('admin.showProject', ['adminId' => auth()->user()->id, 'project' => $project, 'slug' => $project->slug])}}">
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
    </form>
</section>
@endsection