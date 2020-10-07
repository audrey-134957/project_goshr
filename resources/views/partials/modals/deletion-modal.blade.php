<!-- * Extension du model * -->
@extends('partials.modals.modal-layout')

<!-- * Content * -->

@section('modal-content')
<p class="box__modal-text">{{$question}}</p>
<br>
<p>{{$complementaryText}}</p>
@endsection

@section('modal-footer')
<footer class="box__modal-card-footer modal-card-foot">
    <form action="{{$route}}" method="POST">
        @method('DELETE')
        @csrf
        <button type="submit" class="box__modal-button box__modal-button--delete is-rounded button is-danger">{{$confirmChoice}}</button>
        <button type="button" class="modal-close-button  is-rounded button">Non</button>
    </form>
</footer>
@endsection