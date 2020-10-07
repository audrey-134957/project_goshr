@component('mail::message')

# {{$user->username}},

{{$commentReply->user->username}} a répondu à ton commentaire.

<div class="comment-box">
    {{$commentReply->content}}
</div>


@component('mail::button', ['url' => route('projects.show', ['project' => $project->id, 'slug' => $project->slug]).'#commentaire-'.$commentReply->id])
    Voir plus
@endcomponent



L'équipe Goshr.

@endcomponent