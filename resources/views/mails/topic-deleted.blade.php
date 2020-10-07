@component('mail::message')

# {{$user->username}},



Vous avez laissé un topic le {{$topic->getPublishDate()}} pour le projet "{{$project->title}}" .

<div class="topic-box">
"{{$topic->content}}"
</div>


Ne respectant pas les règles établies par Goshr, celui-ci a été supprimé.

Nous sommes navrés de cette décision et espérons que vous continuerez de poster.

Cordialement.

L'équipe Goshr.

@endcomponent
