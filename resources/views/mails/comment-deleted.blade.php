@component('mail::message')

# {{$commentAuthor->username}},



Vous avez laissé un commentaire le {{$comment->getPublishDate()}} pour le projet "{{$project->title}}" .

<div class="comment-box">
"{{$comment->content}}"
</div>


Ne respectant pas les règles établies par Goshr, celui-ci a été supprimé.

Nous sommes navrés de cette décision et espérons vous voir poster prochainement.

Cordialement.

L'équipe Goshr.

@endcomponent