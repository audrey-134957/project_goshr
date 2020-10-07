@if(session('status'))
<div class="notification notification--success is-success">
    <p class="notification__text">{{ session('status') }}</p>
</div>

@elseif(session('error'))
<div class="notification notification--danger is-danger">
    <p class="notification__text">{{ session('error') }}</p>
</div>
@endif