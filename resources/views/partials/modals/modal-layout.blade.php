<!-- * Modal * -->
<div class="modal">
    <!-- * Modal Background * -->
    <div class="modal-background"></div>

    <!-- * Card * -->
    <div class="modal-card">

        <!-- * Header * -->
        <header class="modal-card-head">
            <h1 class="modal-card-title">{{$modalTitle}}</h1>
        </header>

        <!-- * Body * -->
        <div class="modal-body">
            @yield('modal-body')

            <!-- * Content * -->
            <section class="modal-card-body">
                @yield('modal-content')
            </section>

            <!-- * Footer * -->
            @yield('modal-footer')
        </div>
    </div>
    <button class="modal-close is-large is-active" aria-label="close"></button>
</div>