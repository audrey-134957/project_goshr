<!-- * Modal * -->
<div class="modal">
    <!-- * Modal Background * -->
    <div class="modal-background"></div>

    <!-- * Card * -->
    <div class="modal-card">

        <!-- * Header * -->
        <header class="modal-card-head">
            <h1 class="modal-card-title">Rejoignez la communauté Goshr!</h1>
        </header>

        <!-- * Body * -->
        <div class="modal-body">
            <!-- * Content * -->
            <section class="modal-card-body">
                <div class="cta-content-box">
                    <p><strong>Je veux faire partie de la communauté</strong></p>
                    <a href="{{ route('register.create') }}" class="cta-content-box__link cta-content-box__link--is-submit button is-rounded">Je m'inscris !</a>

                    <hr>
                    <p><strong>Je possède déjà un compte</strong></p>
                    <a href="{{ route('login.create') }}" class="cta-content-box__link cta-content-box__link--is-login button is-rounded">je me connecte!</a>
                </div>
            </section>

        </div>
    </div>
    <button class="modal-close is-large is-active" aria-label="close"></button>
</div>