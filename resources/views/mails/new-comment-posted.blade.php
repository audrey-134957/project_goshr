@component('mail::message')

# {{$user->username}},


{{$comment->user->username}} a laissé un commentaire pour ton projet "{{$project->title}}".

<div class="comment-box">
    {{$comment->content}}
</div>


@component('mail::button', ['url' => route('projects.show', ['project' => $project->id, 'slug' => $project->slug]).'#commentaire-'.$comment->id])
    Voir plus
@endcomponent



L'équipe Goshr.

@endcomponent