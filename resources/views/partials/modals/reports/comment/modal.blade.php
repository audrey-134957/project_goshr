<!-- * Modal * -->
<div class="modal">
    <!-- * Modal Background * -->
    <div class="modal-background"></div>

    <!-- * Card * -->
    <div class="modal-card">

        <!-- * Header * -->
        <header class="modal-card-head">
            <h1 class="modal-card-title">Signalement du commentaire</h1>
        </header>

        <!-- * Body * -->
        <div class="modal-body">
            <form action="{{route('reports.storeCommentReport', ['comment' => $comment, 'user' => auth()->user()])}}" method="POST">
                @csrf

                <!-- * Content * -->
                <section class="modal-card-body">
                    @foreach($motives as $motive)
                    <label class="checkbox" style="display: block;line-height:2.5rem;">
                        <input type="checkbox" name="motives[]" value="{{$motive->id}}">
                        {{$motive->name}}
                    </label>
                    @endforeach
                </section>

                <!-- * Footer * -->
                <footer class="modal-card-foot">
                    <button type="submit" class="modal-submit-button button is-rounded is-success">Je signale</button>
                    <button type="button" class="modal-close-button button is-rounded">Annuler</button>
                </footer>
            </form>
        </div>
    </div>
    <button class="modal-close is-large is-active" aria-label="close"></button>
</div>