@extends('partials.admin-base-layout')

@section('admin-title', "signalement n° $report->id")

@section('admin-header-subtitle', "signalement n° $report->id")

@section('layout-content')

<div class="report-card">
    <div class="report-card__report-infos reports-infos">

        <figure class="project-card__image image is-40x40">
            <img class="is-rounded" src="{{$report->user->getImage($report->user)}}" alt="Placeholder image">
        </figure>

        {!! $report->content() !!}
    </div>

    <div class="report-card__motives-box">
        @foreach($report->motives as $motive)
        <span class="report-card__tag tag is-rounded is-info is-light">{{$motive->name}}</span>
        @endforeach
    </div>
</div>

<div class="reported-content project-article project-article--reported-content box" id="projet">

    <h1 class="project-article__title project-article__title--project title">{{$project->title}}</h1>

    <div class="project-article__infos">
        <span class="project-article__category category project__tag">{{$project->category->name}}</span>

        <span class="project-article__difficulty-level project-article__difficulty-level--{{$project->difficulty_level->en_name}}">{{$project->difficulty_level->name}}</span>

        <span class="project-article__duration"><i class="project-article__icon project-article__icon--clock fa fa-clock-o" aria-hidden="true"></i>
            {{$project->getDuration()}}</span>


        <span class="project-article__materials"><i class="project-article__icon project-article__icon--material fa fa-shopping-basket" aria-hidden="true"></i>
            {{$project->materials->count(). ' mat.'}}</span>

    </div>

    <div class="project-article__media media">
        <a href="{{route('profiles.indexPublishedProjects', ['user' => $project->user])}}">
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

<form class="decision-report-form" action="{{route('admin.storeAdminDecisionForProjectReport', ['adminId' => auth()->user()->id, 'report'=> $report, 'project' => $project])}}" method="POST">
    @csrf
    @method('PATCH')
    <button class="button is-danger is-light is-rounded" type="submit" name="submit" value="approve">approuver</button>
    <button class="decision-report-form__button decision-report-form__button--disapprove button is-rounded" type="submit" name="submit" value="disapprove">désapprouver</button>
</form>

@endsection