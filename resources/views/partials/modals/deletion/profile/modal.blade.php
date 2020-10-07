<!-- * Modal * -->
<div class="modal">
    <!-- * Modal Background * -->
    <div class="modal-background"></div>

    <!-- * Card * -->
    <div class="modal-card">

        <!-- * Header * -->
        <header class="modal-card-head">
            <h1 class="modal-card-title">Voulez-vous vraiment supprimer votre compte?</h1>
        </header>

        <!-- * Body * -->
        <div class="modal-body">

            <!-- * Content * -->
            <section class="modal-card-body">
                <p>Toutes tes données personnelles, vos projets et posts seront supprimer <strong>de manière définitive</strong></p>
            </section>

            <!-- * Footer * -->
            <footer class="modal-card-foot">
            <form action="{{route('profiles.delete', ['user' => auth()->user()->id])}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="modal-submit-button button is-rounded is-success">Supprimer</button>
                    <button type="button" class="modal-close-button button is-rounded">Annuler</button>
                </form>
            </footer>
        </div>
    </div>
    <button class="modal-close is-large is-active" aria-label="close"></button>
</div>