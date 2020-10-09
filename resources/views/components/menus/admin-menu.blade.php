<nav class="admin-menu is-hidden">
    <div class="admin-menu__header">
        <figure class="image is-64x64">
            <img class="is-rounded" src="{{auth()->user()->getImage(auth()->user())}}">
        </figure>
        <span class="admin-menu__admin-infos"><span class="admin-menu__admin-username">{{auth()->user()->firstname.' '.auth()->user()->name }}</span> <br> {{auth()->user()->role->name}}</span>
    </div>
    <div class="admin-menu__content">

        <ul class="admin-menu__list-items">
            <!-- * Mon compte * -->
            <li class="admin-menu__item admin-menu__item--category">
                <a href="{{route('admin.edit', ['adminId' => auth()->user()->id, 'token' => auth()->user()->token_account])}}" class="admin-menu__link">
                    <i class="admin-menu__icon fa fa-cogs" aria-hidden="true"></i>
                    <span class="admin-menu__link-name">Mon compte</span>
                </a>
            </li>
            <hr>
            <!-- * Comptes utilisateurs * -->
            <li class="admin-menu__item admin-menu__item--category">
                <a href="{{route('admin.indexUsers', ['adminId' => auth()->user()->id])}}" class="admin-menu__link">
                    <i class="admin-menu__icon fa fa-users" aria-hidden="true"></i>
                    <span class="admin-menu__link-name">Comptes utilisateurs</span>
                </a>
            </li>
            <hr>
            <!-- * Ajouter un utilisateur * -->
            <ul class="admin-menu__sublist-items">
                <li class="admin-menu__item admin-menu__item--category">
                    <a href="{{route('admin.createUser', ['adminId' => auth()->user()->id])}}" class="admin-menu__link">
                        <i class="admin-menu__icon fa fa-plus" aria-hidden="true"></i>
                        <span class="admin-menu__link-name">Ajouter un utilisateur</span>
                    </a>
                </li>
                <hr>
                <!-- * Utilisateurs bannis * -->
                <li class="admin-menu__item admin-menu__item--category">
                    <a href="{{route('admin.indexBans', ['adminId' => auth()->user()->id])}}" class="admin-menu__link">
                        <i class="admin-menu__icon fa fa-ban" aria-hidden="true"></i>
                        <span class="admin-menu__link-name">Utilisateurs bannis</span>
                    </a>
                </li>
            </ul>
            <hr>
            <!-- * Projets * -->
            <li class="admin-menu__item admin-menu__item--category">
                <a href="{{route('admin.indexProjects', ['adminId' => auth()->user()->id])}}" class="admin-menu__link">
                    <i class="admin-menu__icon fa fa-th-large" aria-hidden="true"></i>
                    <span class="admin-menu__link-name">Projets</span>
                </a>
            </li>
            <hr>
            <!-- * Signalements * -->
            <li class="admin-menu__item admin-menu__item--category">
                <a href="{{route('admin.indexReports', ['adminId' => auth()->user()->id])}}" class="admin-menu__link">
                    <i class="admin-menu__icon fa fa-ban" aria-hidden="true"></i>
                    <span class="admin-menu__link-name">Signalements</span>
                </a>
            </li>
            <hr>
            <!-- * Compets administrateurs * -->
            @if(auth()->user()->role->name === 'Super administrateur')
            <li class="admin-menu__item admin-menu__item--category">
                <a href="{{route('admin.indexAdmins', ['adminId' => auth()->user()->id])}}" class="admin-menu__link">
                    <i class="admin-menu__icon fa fa-user-secret" aria-hidden="true"></i>
                    <span class="admin-menu__link-name">Comptes admins.</span>
                </a>
            </li>
            <hr>
            <ul class="admin-menu__sublist-items">
                <!-- * Ajouter un administrateur * -->
                <li class="admin-menu__item admin-menu__item--category">
                    <a href="{{route('admin.createAdmin', ['adminId' => auth()->user()->id])}}" class="admin-menu__link">
                        <i class="admin-menu__icon fa fa-plus" aria-hidden="true"></i>
                        <span class="admin-menu__link-name">Ajouter un admin.</span>
                    </a>
                </li>
            </ul>
            <hr>
            <!-- * Catégories * -->
            <li class="admin-menu__item admin-menu__item--category">
                <a href="{{route('admin.indexCategories', ['adminId' => auth()->user()->id])}}" class="admin-menu__link">
                    <i class="admin-menu__icon fa fa-tags" aria-hidden="true"></i>
                    <span class="admin-menu__link-name">Catégories</span>
                </a>
            </li>
            <hr>
            @endif
        </ul>
    </div>
</nav>