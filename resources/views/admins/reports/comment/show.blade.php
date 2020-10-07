@extends('partials.admin-base-layout')

@section('admin-title', "signalement n° $report->id")

@section('admin-header-subtitle', "signalement n° $report->id")

@section('layout-content')
<div class="report-header">
    <div class="report-header__report-infos">

        <figure class="project-card__image image is-40x40">
            <img class="is-rounded" src="{{$report->reportAuthorAvatar()}}" alt="Placeholder image">
        </figure>

        {!! $report->content() !!}
    </div>

    <div class="report-header__motives-list">
        @foreach($report->motives as $motive)
        <span class="report-header__tag tag is-rounded is-info is-light">{{$motive->name}}</span>
        @endforeach
    </div>
</div>

<div class="comment-card comment-card--back">
    <div class="comment-card__content">
        <div class="media">
            <div class="media-left">
                <figure class="image is-32x32">
                    <img class="is-rounded" src="{{$comment->user->getImage($comment->user)}}" alt="Placeholder image">
                </figure>
            </div>
        </div>

        <div class="comment-card__right-part">
            <span class="comment-card__author-username">{{$comment->user->username}}</span>
            <time class="comment-card__publish-date">le {{$comment->getPublishDate()}}</time>

            <p class="comment-card__comment">{{$comment->content}}</p>
        </div>
    </div>
</div>

<form  class="report-decision-form" action="{{route('admin.storeAdminDecisionForCommentReport', ['adminId' => auth()->user()->id, 'report'=> $report,'comment' => $comment])}}" method="POST">
    @csrf
    @method('PATCH')
    <button class="button is-danger is-light is-rounded" type="submit" name="submit" value="approve">approuver</button>
    <button class="button is-rounded" type="submit" name="submit" value="disapprove" style="margin-left:1rem;">désapprouver</button>
</form>

@endsection