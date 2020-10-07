@component('mail::message')


Un message été envoyé par un utilisateur:
<hr>
<div class="box">
    <h1>{{$message['subject']}}</h1>
    <p>{{$message['message']}}</p>
</div>


@endcomponent