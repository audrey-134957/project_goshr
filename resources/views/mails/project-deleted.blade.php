@component('mail::message')

# {{$projectAuthor->username}},


Votre projet "{{$project->title}}" a été supprimé.

Ne respectant pas les règles établies par Goshr, celui-ci a été supprimé.

Nous sommes navrés de cette décision et espérons que vous continuerez de partager d'autres projets à l'avenir.

Cordialement.

L'équipe Goshr.

@endcomponent
