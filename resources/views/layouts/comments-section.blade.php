<aside class="comments-section box" id="commentaires">
    <h2 class="comments-section__title title">{{$project->comments->count() . ' ' . $project->getCommentsBoxTitle()}}</h2>
    <hr>
    <div class="comments-section__box-content">
        <!-- ------------------- PREMIER CHAMPS DE CREATION DU COMMENTAIRE ---------------------- -->
        <div class="comments-section__textzone @guest comments-section__textzone--call-to-action @endguest">


            @auth
            <div class="comments-section__media media">
                <div class="media-left">
                    <figure class="image is-32x32">
                        <img class="is-rounded" src="{{auth()->user()->getImage(auth()->user())}}">
                    </figure>
                </div>
            </div>
            @endauth

            <div class="comments-section__textzone section__box section__box--comment">
                <form class="submit_comment_form" action="{{route('comments.store', [$project, $slug])}}" method="POST">
                    @csrf
                    <textarea class="comments-section__textarea textarea @error('comment_content') is-danger @enderror" name="comment_content" placeholder="laissez un commentaire pour ce projet"></textarea>
                    <button class="comments-section__button comments-section__button--submit button is-rounded" @guest type="button" @else type="submit" @endguest>ajouter</button>
                </form>
                @error('comment_content')
                <p class="help is-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>

        @guest
        @include('partials.modals.call-to-action.modal')
        @endguest

        <!-- ------------------- FIN: PREMIER CHAMPS DE CREATION DU COMMENTAIRE ---------------------- -->


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
                        <time class="comment-card__publish-date">le {{$comment->getPublishDate()}}</time>

                        <p class="comment-card__comment">{{$comment->content}}</p>
                    </div>
                </div>
                <!-- ------------------- SECTION DE CHAMPS POUR REPONDRE MODIFIER LE COMMENTAIRE ---------------------- -->

                <div class="comment-card__footer">
                    @auth

                    @php
                    $reportedComment = $comment->reports()->where('user_id', auth()->user()->id)->get();
                    @endphp

                    @can('doReport', $comment)

                    @if($reportedComment->count() === 0)
                    <button class="comment-card__button comment-card__button--report-modal modal-button button is-white is-rounded" type="button">
                        <i class="comment-card__report-icon comment-card__icon fa fa-ban" aria-hidden="true"></i>
                    </button>

                    @include('partials.modals.reports.comment.modal')
                    
                    @else
                    <span class="comment-card__report-tag tag is-medium is-right">
                        <i class="comment-card__check-report-icon fa fa-check" aria-hidden="true"></i>
                        signalé
                    </span>
                    @endif
                    @endcan



                    @can('update', $comment)
                    <!-- ------------------- BOUTON MODIFIER LE COMMENTAIRE ---------------------- -->

                    <button class="comment-card__button comment-card__button--edit button is-white is-rounded" type="button"><i class="comment-card__icon comment-card__icon--pencil fa fa-pencil" aria-hidden="true"></i></button>
                    <!-- ------------------- END: BOUTON POUR MODIFIER LE COMMENTAIRE ---------------------- -->
                    @endcan
                    <!-- ------------------- BOUTON POUR RÉPONDRE COMMENTAIRE ---------------------- -->

                    <button class="comment-card__button comment-card__button--reply button is-white is-rounded" type="button"><i class="comment-card__icon comment-card__icon--reply fa fa-reply" aria-hidden="true"></i></button>
                    <!-- ------------------- END: BOUTON POUR RÉPONDRE COMMENTAIRE ---------------------- -->

                    <!-- ------------------- SECTION DE CHAMPS POUR MODIFIER LE COMMENTAIRE ---------------------- -->

                    <div class="comment-card__textzone is-hidden">
                        <form class="edit-comment-form" action="{{route('comments.update', [$project, $slug, $comment])}}" method="POST">
                            @method('PATCH')
                            @csrf
                            <textarea class="comment-card__textarea textarea @error('edit_comment_content') is-danger @enderror" name="edit_comment_content">{{$comment->content}}</textarea>
                            <button class="comment-card__button comment-card__button--submit-edit button is-rounded" type="submit">modifier</button>
                        </form>
                        @error('edit_comment_content')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ------------------- END: SECTION DE CHAMPS POUR MODIFIER LE COMMENTAIRE ---------------------- -->

                    <!-- ------------------- SECTION DE CHAMPS POUR REPONDRE AU COMMENTAIRE ---------------------- -->

                    <div class="comment-card__textzone comment-card__textzone--reply is-hidden">
                        <form class="submit_comment_reply_form" action="{{route('comments.storeReply', ['project' => $project, 'slug' => $slug, 'comment' => $comment])}}" method="POST">
                            @csrf
                            <textarea class="comment-card__textarea textarea @error('comment_reply_content') is-danger @enderror" name="comment_reply_content"></textarea>
                            <button class="comment-card__button comment-card__button--submit-reply button is-rounded" type="submit">répondre</button>
                        </form>
                        @error('comment_reply_content')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- ------------------- FIN: SECTION DE CHAMPS POUR REPONDRE AU COMMENTAIRE ---------------------- -->

                    @endauth
                    <!-- ------------------- SECTION DE CHAMPS POUR MODIFIER LE COMMENTAIRE ---------------------- -->
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
                        <time class="comment-card__publish-date">le {{$commentReply->getPublishDate()}}</time>

                        <p class="comment-card__comment">{{$commentReply->content}}</p>
                    </div>
                </div>

                <!-- ------------------- SECTION DE CHAMPS POUR MODIFIER LE COMMENTAIRE ---------------------- -->



                @auth

                @php

                // je vérifie si mon topic a déjà été reporté par l'utilisateur connecté;

                $reportedReplyComment = $commentReply->reports()->where('user_id', auth()->user()->id)->get();
                @endphp

                <div class="comment-card__footer">
                    @can('doReport', $commentReply)
                    @if($reportedReplyComment->count() === 0)
                    <button class="comment-card__button comment-card__button--report-modal modal-button button is-rounded is-white" type="button">
                        <i class="comment-card__report-icon comment-card__icon fa fa-ban" aria-hidden="true"></i>
                    </button>

                    @include('partials.modals.reports.comment-reply.modal')
                    @else
                    <span class="comment-card__report-tag tag is-medium is-right">
                        <i class="comment-card__check-report-icon fa fa-check" aria-hidden="true"></i>
                        signalé
                    </span>
                    @endif
                    @endcan


                    @can('update', $commentReply)
                    <!-- ------------------- BOUTON POUR MODIFIER LE COMMENTAIRE ---------------------- -->
                    <button class="comment-card__button comment-card__button--edit button is-white is-rounded" type="button"><i class="comment-card__icon comment-card__icon--pencil fa fa-pencil" aria-hidden="true"></i></button>
                    <!-- ------------------- end: BOUTON POUR MODIFIER LE COMMENTAIRE ---------------------- -->
                    @endcan
                    <!-- ------------------- BOUTON POUR REPONDRE AU COMMENTAIRE ---------------------- -->

                    <button class="comment-card__button comment-card__button--reply button is-white is-rounded" type="button"><i class="comment-card__icon comment-card__icon--reply fa fa-reply" aria-hidden="true"></i></button>
                    <!-- ------------------- end: BOUTON POUR REPONDRE AU COMMENTAIRE ---------------------- -->

                    <div class="comment-card__textzone is-hidden">
                        <form class="edit-comment-form" action="{{route('comments.updateReply', [$project, $slug, $commentReply])}}" method="POST">
                            @method('PATCH')
                            @csrf
                            <textarea class="comment-card__textarea textarea @error('edit_comment_reply_content') is-danger @enderror" name="edit_comment_reply_content">{{$commentReply->content}}</textarea>
                            <button class="comment-card__button comment-card__button--submit-edit button is-rounded" type="submit">modifier</button>
                        </form>
                        @error('edit_comment_reply_content')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>
                    <!-- ------------------- SECTION DE CHAMPS POUR REPONDRE AU COMMENTAIRE ---------------------- -->

                    <div class="comment-card__textzone comment-card__textzone--reply is-hidden">
                        <form class="submit_comment_reply_form" action="{{route('comments.storeReply', ['project' => $project, 'slug' => $slug, 'comment' => $comment])}}" method="POST">
                            @csrf
                            <textarea class="comment-card__textarea textarea @error('comment_reply_content') is-danger @enderror" name="comment_reply_content"></textarea>
                            <button class="comment-card__button comment-card__button--submit-reply button is-rounded" type="submit">répondre</button>
                        </form>
                        @error('comment_reply_content')
                        <p class="help is-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ------------------- end: SECTION DE CHAMPS POUR REPONDRE AU COMMENTAIRE ---------------------- -->

                    <!-- ------------------- FIN: SECTION DE CHAMPS POUR MODIFIER LE COMMENTAIRE ---------------------- -->


                </div>

                @endauth

            </div>
            <!-- ------------------- fin de condition [1.2]---------------------- -->
            @endforeach
            <!-- ------------------- s'il n'y a aucun commentaire [2] ---------------------- -->
            @empty
            <div class="comments-section__no-comment-box">
                <p>Il y a aucun commentaire pour le moment. Tu peux écrire le premier !</p>
            </div>
            <!-- ------------------- fin de condition [3] ---------------------- -->
            @endforelse

        </div>
    </div>
</aside>