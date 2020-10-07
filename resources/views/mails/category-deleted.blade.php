@component('mail::message')

# Bonjour {{$user->username}} !


Il y a eu du nouveau dans les catégories.

La catégorie <strong>{{$category->name}}</strong> a été supprimé. Tes projets étaient dans cette catégorie? Ne t'en fais pas, ce changement n'affecte en rien la publication de tes projets. 

Nous te souhaitons une bonne journée.


L'équipe Goshr.

@endcomponent