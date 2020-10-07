<div class="menu is-hidden">
    <input type="radio" id="menu" name="nav-tab" checked>
    <input type="radio" id="notif" name="nav-tab">

    <div class="menu__tabs tabs is-centered">
        <ul class="menu__list-items menu__list-items--is-header">
            <li class="menu__item"><label for="menu"><a class="menu__link">Menu</a></label></li>
            <li class="menu__item"><label for="notif"><a class="menu__link">Notifications
                        @unless(auth()->user()->unreadNotifications->isEmpty())
                        <span class="menu__notification-bullet">&#9679;</span>
                        @endunless</a></label></li>
        </ul>
    </div>

    <div class="menu__tab-panes tab-content">
        <div class="menu__tab-pane tab-pane content-menu">
            <nav>
                <ul class="menu__list-items menu__list-items--content">
                    <li class="menu__item"><a href="{{route('projects.index')}}" class="menu__link {{request()->route()->named('projets.*') ? 'menu__link--is-active' : '' }} menu__link--hide">Projets</a></li>
                    <li class="menu__item"><a href="{{route('projects.create')}}" class="menu__link {{request()->route()->named('projets.create') ? 'menu__link--is-active' : '' }}">Poster un projet</a></li>
                    <hr>
                    <li class="menu__item"><a href="{{route('profiles.indexPublishedProjects', ['user' => auth()->user()->username])}}" class="menu__link {{request()->route()->named('profiles.indexPublishedProjects') && strpos(request()->url(), auth()->user()->username) ? 'menu__link--is-active' : '' }}">Mon profil</a></li>
                    <li class="menu__item"><a href="{{route('logout.create')}}" class="menu__link">Déconnexion</a></li>
                </ul>
            </nav>
        </div>
        <div class="menu__tab-pane tab-pane content-notif">
            <div class="menu__notification-list">
                <!-- à moins que les notifications ne soit lus par l'utilisateur -->
                @unless(auth()->user()->unreadNotifications->isEmpty())
                <!-- pour chaque notification-->


                @foreach(auth()->user()->unreadNotifications as $notification)

                @php
                $notification_date = \Carbon\Carbon::parse($notification->created_at)->locale('fr');
                $transform_notification_date = $notification_date->isoFormat('D MMM YYYY à HH:mm');
                @endphp

                @if($notification->type == 'App\Notifications\NewCommentReplyPosted')


                <a href="{{route('notifications.showCommentFromNotification', ['project' => $notification->data['projectId'], 'notification' => $notification->id])}}#commentaire-{{$notification->data['commentReplyId']}}" class="menu__notification-link">
                    <div class="menu__notification">
                        <span class="menu__notification-bullet">&#9679;</span>
                        <div class="menu__notification-detail">
                            <p class="menu__notification-text">{{$notification->data['userUsername']}} a répondu à votre commentaire pour le projet "<strong>{{$notification->data['projectTitle']}}</strong>".</p>
                        </div>
                        <time class="menu__notification-time">{{$transform_notification_date}}</time>
                    </div>
                </a>
                <hr>

                @elseif($notification->type == 'App\Notifications\NewTopicReplyPosted')

                <a href="{{route('notifications.showTopicFromNotification', ['project' => $notification->data['projectId'], 'notification' => $notification->id])}}#topic-{{$notification->data['topicReplyId']}}" class="menu__notification-link">
                    <div class="menu__notification">
                        <span class="menu__notification-bullet">&#9679;</span>
                        <div class="menu__notification-detail">
                            <p class="menu__notification-text">{{$notification->data['userUsername']}} a répondu à la question posée pour le projet "<strong>{{$notification->data['projectTitle']}}</strong>".</p>
                        </div>
                        <time class="menu__notification-time">{{$transform_notification_date}}</time>
                    </div>
                </a>
                <hr>

                @elseif($notification->type == 'App\Notifications\NewTopicPosted')

                <a href="{{route('notifications.showTopicFromNotification', ['project' => $notification->data['projectId'], 'notification' => $notification->id])}}#topic-{{$notification->data['topicId']}}" class="menu__notification-link">
                    <div class="menu__notification">
                        <span class="menu__notification-bullet">&#9679;</span>
                        <div class="menu__notification-detail">
                            <p class="menu__notification-text">{{$notification->data['userUsername']}} vient de poser une question pour le projet "<strong>{{$notification->data['projectTitle']}}</strong>".</p>
                        </div>
                        <time class="menu__notification-time">{{$transform_notification_date}}</time>
                    </div>
                </a>
                <hr>
                @else

                <a href="{{route('notifications.showCommentFromNotification', ['project' => $notification->data['projectId'], 'notification' => $notification->id])}}#commentaire-{{$notification->data['commentId']}}" class="menu__notification-link">
                    <div class="menu__notification">
                        <span class="menu__notification-bullet">&#9679;</span>
                        <div class="menu__notification-detail">
                            <p class="menu__notification-text">{{$notification->data['userUsername']}} vient de poster un commentaire pour le projet "<strong>{{$notification->data['projectTitle']}}</strong>".</p>
                        </div>
                        <time class="menu__notification-time">{{$transform_notification_date}}</time>
                    </div>
                </a>
                <hr>

                @endif

                @endforeach
                <!-- si les notifications ont été lus -->
                @else
                <p class="menu__notification-text-no-notification">Vous n'avez aucune notification pour le moment.</p>
                @endunless
                <!-- fin de la condition -->
            </div>
        </div>
    </div>
</div>
