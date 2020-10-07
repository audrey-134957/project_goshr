@extends('partials.admin-base-layout')

@section('admin-title', "projet n° $project->id")

@section('admin-header-subtitle', "projet n° $project->id")

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
                <button class="project-article__button button is-rounded is-danger is-outlined modal-button" type="button"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                @include('partials.modals.deletion.project.admin-modal')
                @endauth
            </div>
        </div>

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


    <aside class="topics-section section--Q-A box" id="q-a">
        @php
        if($project->topics->count() <= 1){ $topicTitle='question' ; } else{ $topicTitle='questions' ; } @endphp <h1 class="topics-section__title title">{{$project->topics->count() . ' ' . $topicTitle}}</h1>
            <hr>
            <div class="topics-section__box-content">

                <div class="topics-section__list @if( !auth()->check() || auth()->check() && auth()->user()->id !== $project->user_id)  topics-section__list--margin-top @endif">
                    @forelse($project->topics as $topic)
                    @php
                    $topic_id = $topic->id;
                    $topic_creation_date = \Carbon\Carbon::parse($topic->created_at)->locale('fr');
                    $transform_topic_creation_date = $topic_creation_date->isoFormat('D MMM YYYY à HH:mm');
                    @endphp
                    <div class="topic-card" id="topic-{{$topic->id}}">
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
                                <time class="topic-card__publish-date">le {{$transform_topic_creation_date}}</time>

                                <p class="topic-card__topic">{{$topic->content}}</p>
                            </div>
                        </div>
                        <!-- ------------------- SECTION DE CHAMPS POUR REPONDRE MODIFIER LE TOPIC ---------------------- -->

                        <div class="topic-card__footer">
                            @auth

                            <!-- ------------------- BOUTON MODIFIER LE TOPIC ---------------------- -->

                            <button class="topic-card__button topic-card__button--edit button modal-button is-white is-rounded" type="button"><i class="topic-card__icon topic-card__icon--pencil fa fa-trash-o" aria-hidden="true"></i></button>
                            @include('partials.modals.deletion.topic.admin-modal')

                            <!-- ------------------- END: BOUTON POUR MODIFIER LE TOPIC ---------------------- -->

                            @endauth
                            <!-- ------------------- SECTION DE CHAMPS POUR MODIFIER LE TOPIC ---------------------- -->
                        </div>

                        <!-- ------------------- SECTION DE CHAMPS POUR MODIFIER LE TOPIC ---------------------- -->
                    </div>



                    <!-- ------------------- pour chaque topic enfant [1.1]---------------------- -->

                    @foreach($topic->topics as $topicReply)
                    @php
                    $topic_id = $topicReply->id;
                    $topicReply_creation_date = \Carbon\Carbon::parse($topicReply->created_at)->locale('fr');
                    $transform_topic_reply_creation_date = $topicReply_creation_date->isoFormat('D MMM YYYY à HH:mm');
                    @endphp

                    <div class="topic-card topic-card--reply section__author-infos section__author-infos--topics section__author-infos--topics--reply" id="topic-{{$topicReply->id}}">

                        <div class="topic-card__content">
                            <div class="media">
                                <div class="media-left">
                                    <figure class="image is-32x32">
                                        <img class="is-rounded" src="{{$topicReply->user->getImage($topic->user)}}" alt="Placeholder image">
                                    </figure>
                                </div>
                            </div>

                            <div class="topic-card__right-part">
                                <span class="topic-card__author-username">{{$topicReply->user->username}}</span>
                                <time class="topic-card__publish-date">le {{$transform_topic_creation_date}}</time>

                                <p class="topic-card__topic">{{$topicReply->content}}</p>
                            </div>
                        </div>



                        @auth
                        <div class="topic-card__footer">
                            <button class="topic-card__button topic-card__button--edit modal-button button is-white is-rounded" type="button"><i class="topic-card__icon topic-card__icon--pencil fa fa-trash-o" aria-hidden="true"></i></button>
                            @include('partials.modals.deletion.topic-reply.admin-modal')

                        </div>
                        @endauth
                    </div>
                    <!-- ------------------- fin de condition [1.2]---------------------- -->
                    @endforeach
                    <!-- ------------------- s'il n'y a aucun topic [2] ---------------------- -->

                    @empty
                    <div class="topics-section__no-topic-box">
                        <p>Aucune question en vue pour le moment !</p>
                    </div>
                    <!-- ------------------- fin de condition [3] ---------------------- -->
                    @endforelse
                </div>
            </div>
    </aside>




    <aside class="comments-section box" id="commentaires">
        @php
        if($project->comments->count() <= 1){ $title='commentaire' ; }else{ $title='commentaires' ; } @endphp <h2 class="comments-section__title title">{{$project->comments->count() . ' ' . $title}}</h2>
            <hr>
            <div class="comments-section__box-content">
                <div class="comments-section__list">
                    @forelse($project->comments as $comment)
                    <div class="comment-card" id="commentaire-{{$comment->id}}">
                        <div class="comment-card__content">
                            <div class="media">
                                <div class="media-left">
                                    <figure class="image is-32x32">
                                        <img class="is-rounded" src="{{$comment->user->getImage($project->user)}}" alt="Placeholder image">
                                    </figure>
                                </div>
                            </div>

                            <div class="comment-card__right-part">
                                <span class="comment-card__author-username">{{$comment->user->username}}</span>
                                <time class="comment-card__publish-date">le {{$comment->getPublishDate() }}</time>

                                <p class="comment-card__comment">{{$comment->content}}</p>
                            </div>
                        </div>

                        <div class="comment-card__footer">
                            @auth
                            <button class="comment-card__button comment-card__button--edit modal-button button is-white is-rounded" type="button"><i class="comment-card__icon comment-card__icon--pencil fa fa-trash-o" aria-hidden="true"></i></button>
                            @include('partials.modals.deletion.comment.admin-modal')
                            @endauth
                        </div>
                    </div>

                    <!-- ------------------- pour chaque commentaire enfant [1.1]---------------------- -->

                    @foreach($comment->comments as $commentReply)
                    <div class="comment-card comment-card--reply section__comment-box-header section__author-infos section__author-infos--comments section__author-infos--comments--reply">

                        <div class="comment-card__content">
                            <div class="media">
                                <div class="media-left">
                                    <figure class="image is-32x32">
                                        <img class="is-rounded" src="{{$commentReply->user->getImage($project->user)}}" alt="Placeholder image">
                                    </figure>
                                </div>
                            </div>

                            <div class="comment-card__right-part">
                                <span class="comment-card__author-username">{{$commentReply->user->username}}</span>
                                <time class="comment-card__publish-date">le {{$ommentReply->getPublishDate()}}</time>

                                <p class="comment-card__comment">{{$commentReply->content}}</p>
                            </div>
                        </div>

                        @auth

                        <div class="comment-card__footer">
                            <button class="comment-card__button comment-card__button--edit modal-button button is-white is-rounded" type="button"><i class="comment-card__icon comment-card__icon--pencil fa fa-trash-o" aria-hidden="true"></i></button>
                            @include('partials.modals.deletion.comment-reply.admin-modal')
                        </div>

                        @endauth

                    </div>
                    <!-- ------------------- fin de condition [1.2]---------------------- -->
                    @endforeach
                    <!-- ------------------- s'il n'y a aucun commentaire [2] ---------------------- -->
                    @empty
                    <div class="comments-section__no-comment-box">
                        <p>Il y a aucun commentaire pour le moment.</p>
                    </div>
                    <!-- ------------------- fin de condition [3] ---------------------- -->
                    @endforelse

                </div>
            </div>
    </aside>
</article>


@endsection