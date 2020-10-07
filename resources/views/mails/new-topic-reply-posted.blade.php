@component('mail::message')

# {{$user->username}},

{{$topicReply->user->username}} a répondu à ton topic.

<div class="topic-box">
    {{$topicReply->content}}
</div>


@component('mail::button', ['url' => route('projects.show', ['project' => $project->id, 'slug' => $project->slug]).'#topic-'.$topicReply->id])
    Voir plus
@endcomponent



L'équipe Goshr.

@endcomponent