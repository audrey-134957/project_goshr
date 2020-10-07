@component('mail::message')

# Bonjour {{$user->username}} !


Il y a eu du nouveau dans les catégories!

La catégorie <strong>{{$category->name}}</strong> a été créée ! Nous sommes impatient de connaître tes nouveaux projets. En espérant que tu en fera bonne usage ;).

<br/>

L'équipe Goshr.

@endcomponent