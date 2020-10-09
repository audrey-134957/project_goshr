<nav class="navbar">
    <a href="@auth {{route('projects.index')}} @else {{route('home.index')}} @endauth"><img class="navbar__logo" style="width:30px;" src="{{asset('./images/logo/logo-r.png')}}" alt=""></a>


    <a href="{{ route('projects.index') }}" class="navbar__link navbar__link--project {{ request()->route()->named('projects.*') ? 'navbar__link--is-active' : '' }}">
    <i class="navbar__icon navbar__icon--project fa fa-th-large" aria-hidden="true"></i>
    <span class="navbar__link-name">Projets</span>
    </a>

    <form class="search-form" action="{{route('projectsSearch.result')}}" method="GET">
        <div class="control has-icons-left has-icons-right">
            <input type="search" class="search-form__search-input input is-rounded" name="q" value="{{request()->q ?? '' }}" placeholder="Rechercher un projet, un sujet ...">
            <span class="icon is-small is-right">
                <i class="fa fa-search"></i>
            </span>
        </div>
    </form>

    @auth
    <figure class="navbar__avatar image is-32x32">
        <img class="is-rounded" src="{{auth()->user()->getImage(auth()->user())}}" alt="Placeholder image">
    </figure>
    @else
    <div class="navbar__auth-links">
        <a href="{{ route('login.create') }}"><i class="navbar__icon navbar__icon--auth fa fa-user-o" aria-hidden="true"></i></a>
        <a href="{{ route('login.create') }}" class="navbar__link--is-login button is-rounded">connexion</a>
        <a href="{{ route('register.create') }}" class="navbar__link--is-signup button is-rounded">inscription</a>
    </div>
    @endauth
</nav>