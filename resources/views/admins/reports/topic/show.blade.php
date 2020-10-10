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


<div class="reported-content topic-card">

    <div class="topic-card__content">
        <div class="media">
            <div class="media-left">
                <figure class="image is-32x32">
                    <img class="is-rounded" src="{{$topic->user->getImage($topic->user)}}" alt="Placeholder image">
                </figure>
            </div>
        </div>

        <div class="topic-card__right-part">
            <span class="topic-card__author-username">{{$topic->user->username}}</span>
            <time class="topic-card__publish-date">le {{$topic->getPublishDate()}}</time>

            <p class="topic-card__topic">{{$topic->content}}</p>
        </div>
    </div>
</div>

<form class="decision-report-form" action="{{route('admin.storeAdminDecisionForTopicReport', ['adminId' => auth()->user()->id, 'report'=> $report, 'topic' => $topic])}}" method="POST">
    @csrf
    @method('PATCH')
    <button class="button is-danger is-light is-rounded" type="submit" name="submit" value="approve">approuver</button>
    <button class="decision-report-form__button decision-report-form__button--disapprove button is-rounded" type="submit" name="submit" value="disapprove">désapprouver</button>
</form>

@endsection