<div class="admin-navbar">
    <button class="admin-navbar__button admin-navbar__button--menu button is-success is-outlined is-rounded"><i class="admin-navbar__icon fa fa-bars" aria-hidden="true"></i></button>

    <figure class="admin-navbar__image-figure image is-70xauto">
        <img class="admin-navbar__logo" src="{{asset('./images/logo/logo-r.png')}}" alt="logo du site web">
    </figure>

    <a href="{{route('admin.logout', ['adminId' => auth()->user()->id])}}" class="admin-navbar__link button is-success is-outlined is-rounded"><i class="admin-navbar__icon fa fa-power-off" aria-hidden="true"></i><span class="admin-navbar__link-name">déconnexion</span></a>
</div>