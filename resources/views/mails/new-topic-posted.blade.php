@component('mail::message')

# {{$user->username}},


{{$topic->user->username}} a posé une question pour ton projet "{{$project->title}}"".

<div class="topic-box">
    {{$topic->content}}
</div>


@component('mail::button', ['url' => route('projects.show', ['project' => $project->id, 'slug' => $project->slug]).'#topic-'.$topic->id])
    Voir plus
@endcomponent



L'équipe Goshr.

@endcomponent