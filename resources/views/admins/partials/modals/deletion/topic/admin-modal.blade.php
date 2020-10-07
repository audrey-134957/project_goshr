<!-- * Modal * -->
<div class="modal">
    <!-- * Modal Background * -->
    <div class="modal-background"></div>

    <!-- * Card * -->
    <div class="modal-card">

        <!-- * Header * -->
        <header class="modal-card-head">
            <h1 class="modal-card-title">Suppression du topic n° {{$topic->id}}</h1>
        </header>

        <!-- * Body * -->
        <div class="modal-body">

            <!-- * Content * -->
            <section class="modal-card-body">
                <p>Confirmez-vous la suppression du topic ?</p>
            </section>

            <!-- * Footer * -->
            <footer class="modal-card-foot">
            <form action="{{route('admin.deleteTopic', ['adminId' => auth()->user()->id, 'project' => $project,'slug' => $project->slug,'topic' => $topic])}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="modal-submit-button button is-rounded is-success">Confirmer</button>
                    <button type="button" class="modal-close-button button is-rounded">Annuler</button>
                </form>
            </footer>
        </div>
    </div>
    <button class="modal-close is-large is-active" aria-label="close"></button>
</div>