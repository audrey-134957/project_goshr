<aside class="topics-section section--Q-A box" id="q-a">
    <h1 class="topics-section__title title">{{$project->topics->count() . ' ' . $project->getTopicsBoxTitle()}}</h1>
    <hr>
    <div class="topics-section__box-content">

        <div class="topics-section__textzone @guest topics-section__textzone--call-to-action @endguest">

            @if(auth()->check() && auth()->user()->id !== $project->user_id)
            <div class="topics-section__media media">
                <div class="media-left">
                    <figure class="image is-32x32">
                        <img class="is-rounded" src="{{auth()->user()->getImage(auth()->user())}}">
                    </figure>
                </div>
            </div>
            @endif

            @if(!auth()->check() || auth()->check() && auth()->user()->id !== $project->user_id)
            <div class="topics-section__textzone">
                <form action="{{route('topics.store', [$project, $slug])}}" method="POST">
                    @csrf
                    <textarea class="topics-section__textarea textarea @error('topic_content') is-danger @enderror" name="topic_content" placeholder="poser votre question"></textarea>
                    <button class="topics-section__button topics-section__button--submit button is-rounded" @guest type="button" @else type="submit" @endguest>ajouter</button>
                </form>
                @error('topic_content')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>
            @endif
        </div>


        @guest
        @include('partials.modals.call-to-action.modal')
        @endguest


        <div class="topics-section__list @if( !auth()->check() || auth()->check() && auth()->user()->id !== $project->user_id)  topics-section__list--margin-top @endif">
            @forelse($project->topics as $topic)
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
                        <time class="topic-card__publish-date">le {{$topic->getPublishDate()}}</time>

                        <p class="topic-card__topic">{{$topic->content}}</p>
                    </div>
                </div>
                <!-- ------------------- SECTION DE CHAMPS POUR REPONDRE MODIFIER LE TOPIC ---------------------- -->

                <div class="topic-card__footer">
                    @auth

                    @php
                    // je vérifie si mon topic a déjà été reporté par l'utilisateur connecté;
                    $reportedTopic = $topic->reports()->where('user_id', auth()->user()->id)->get();
                    @endphp

                    @can('doReport', $topic)
                    @if($reportedTopic->count() === 0)
                    <button class="topic-card__button topic-card__button--report-modal modal-button button is-white is-rounded" type="button">
                        <i class="topic-card__report-icon topic-card__icon fa fa-ban" aria-hidden="true"></i>
                    </button>

                    @include('partials.modals.reports.topic.modal')
                    @else
                    <span class="topic-card__report-tag tag is-medium is-right">
                        <i class="topic-card__check-report-icon fa fa-check" aria-hidden="true"></i>
                        signalé
                    </span>
                    @endif
                    @endcan



                    @can('update', $topic)
                    <!-- ------------------- BOUTON MODIFIER LE TOPIC ---------------------- -->

                    <button class="topic-card__button topic-card__button--edit button is-white is-rounded" type="button"><i class="topic-card__icon topic-card__icon--pencil fa fa-pencil" aria-hidden="true"></i></button>
                    <!-- ------------------- END: BOUTON POUR MODIFIER LE TOPIC ---------------------- -->
                    @endcan
                    <!-- ------------------- BOUTON POUR RÉPONDRE TOPIC ---------------------- -->
                    @can('answerToTopic', $topic, $project)
                    <button class="topic-card__button topic-card__button--reply button is-white is-rounded" type="button"><i class="topic-card__icon topic-card__icon--reply fa fa-reply" aria-hidden="true"></i></button>
                    <!-- ------------------- END: BOUTON POUR RÉPONDRE TOPIC ---------------------- -->
                    @endcan
                    <!-- ------------------- SECTION DE CHAMPS POUR MODIFIER LE TOPIC ---------------------- -->

                    <div class="topic-card__textzone is-hidden">
                        <form class="edit-topic-form" action="{{route('topics.update', [$project, $slug, $topic])}}" method="POST">
                            @method('PATCH')
                            @csrf
                            <textarea class="topic-card__textarea textarea @error('edit_topic_content') is-danger @enderror" name="edit_topic_content">{{$topic->content}}</textarea>
                            <button class="topic-card__button topic-card__button--submit-edit button is-rounded" type="submit">modifier</button>
                        </form>
                        @error('edit_topic_content')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ------------------- END: SECTION DE CHAMPS POUR MODIFIER LE TOPIC ---------------------- -->

                    <!-- ------------------- SECTION DE CHAMPS POUR REPONDRE AU TOPIC ---------------------- -->

                    <div class="topic-card__textzone topic-card__textzone--reply is-hidden">
                        <form class="submit_topic_reply_form" action="{{route('topics.storeReply', ['project' => $project, 'slug' => $slug, 'topic' => $topic])}}" method="POST">
                            @csrf
                            <textarea class="topic-card__textarea textarea @error('topic_reply_content') is-danger @enderror" name="topic_reply_content"></textarea>
                            <button class="topic-card__button topic-card__button--submit-reply button is-rounded" type="submit">répondre</button>
                        </form>
                        @error('topic_reply_content')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- ------------------- FIN: SECTION DE CHAMPS POUR REPONDRE AU TOPIC ---------------------- -->

                    @endauth
                    <!-- ------------------- SECTION DE CHAMPS POUR MODIFIER LE TOPIC ---------------------- -->
                </div>

                <!-- ------------------- SECTION DE CHAMPS POUR MODIFIER LE TOPIC ---------------------- -->
            </div>



            <!-- ------------------- pour chaque topic enfant [1.1]---------------------- -->

            @foreach($topic->topics as $topicReply)

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
                        <time class="topic-card__publish-date">le {{$topicReply->getPublishDate()}}</time>

                        <p class="topic-card__topic">{{$topicReply->content}}</p>
                    </div>
                </div>



                @auth

                @php

                // je vérifie si mon topic a déjà été reporté par l'utilisateur connecté;

                $reportedReplyTopic = $topicReply->reports()->where('user_id', auth()->user()->id)->get();
                @endphp

                <div class="topic-card__footer">
                    @can('doReport', $topicReply)
                    @if($reportedReplyTopic->count() === 0)
                    <button class="topic-card__button topic-card__button--report-modal modal-button button is-rounded is-white" type="button">
                        <i class="topic-card__report-icon topic-card__icon fa fa-ban" aria-hidden="true"></i>
                    </button>

                    @include('partials.modals.reports.topic-reply.modal')
                    @else
                    <span class="topic-card__report-tag tag is-medium is-right">
                        <i class="topic-card__check-report-icon fa fa-check" aria-hidden="true"></i>
                        signalé
                    </span>
                    @endif
                    @endcan


                    <!-- ------------------- SECTION DE CHAMPS POUR MODIFIER LE TOPIC ---------------------- -->
                    @can('update', $topicReply)
                    <!-- ------------------- BOUTON POUR MODIFIER LE TOPIC---------------------- -->
                    <button class="topic-card__button topic-card__button--edit button is-white is-rounded" type="button"><i class="topic-card__icon topic-card__icon--pencil fa fa-pencil" aria-hidden="true"></i></button>
                    <!-- ------------------- end: BOUTON POUR MODIFIER LE TOPIC---------------------- -->
                    @endcan
                    <!-- ------------------- BOUTON POUR REPONDRE AU TOPIC---------------------- -->
                    @can('answerToTopic', $topic, $project)
                    <button class="topic-card__button topic-card__button--reply button is-white is-rounded" type="button"><i class="topic-card__icon topic-card__icon--reply fa fa-reply" aria-hidden="true"></i></button>
                    @endcan
                    <!-- ------------------- end: BOUTON POUR REPONDRE AU TOPIC---------------------- -->

                    <div class="topic-card__textzone is-hidden" id="{{$topic->id}}">
                        <form class="edit-topic-form" action="{{route('topics.updateReply', [$project, $slug, $topicReply])}}" method="POST">
                            @method('PATCH')
                            @csrf
                            <textarea class="topic-card__textarea textarea @error('edit_topic_reply_content') is-danger @enderror" name="edit_topic_reply_content">{{$topicReply->content}}</textarea>
                            <button class="topic-card__button topic-card__button--submit-edit button is-rounded">modifier</button>
                        </form>
                        @error('edit_topic_reply_content')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ------------------- SECTION DE CHAMPS POUR REPONDRE AU TOPIC---------------------- -->

                    <div class="topic-card__textzone topic-card__textzone--reply is-hidden">
                        <form class="submit_topic_reply_form" action="{{route('topics.storeReply', ['project' => $project, 'slug' => $slug, 'topic' => $topic])}}" method="POST">
                            @csrf
                            <textarea class="topic-card__textarea textarea @error('topic_reply_content') is-danger @enderror" name="topic_reply_content"></textarea>
                            <button class="topic-card__button topic-card__button--submit-reply button is-rounded" type="submit">répondre</button>
                        </form>
                        @error('topic_reply_content')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ------------------- end: SECTION DE CHAMPS POUR REPONDRE AU TOPIC---------------------- -->


                    <!-- ------------------- FIN: SECTION DE CHAMPS POUR MODIFIER LE TOPIC---------------------- -->
                </div>
                @endauth

                <!-- ------------------- FIN: SECTION DE CHAMPS POUR MODIFIER LE TOPIC ---------------------- -->
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