{{-- @if ( request()->route()->named('projects.index') )--}}
    <nav class="breadcrumb {{-- request()->route()->named('projects.show', [$project, $slug]) ? 'breadcrumb--project' : '' --}}">

        <!-- *** pour créer un breadcrumb ***-->

        <!-- à savoir : un segment = une partie de l'url après le 'http:/localhost/'  ex: http:/localhost/seg1/ -->

        <!-- on récupère tous les segments(array) que l'on stocke dans une variable. -->
        @php
        $segments = Request::segments();
        $href = url('/');
        @endphp

        <ul class="breadcrumb__list-items">
            <li class="breadcrumb__item"><a href="{{route('home.index')}}" class="breadcrumb__link">Accueil</a></li>

            <!-- pour chaque segment contenus dans le tableau 'segments'... -->
            @foreach($segments as $segment)
            <!-- ...on ajoute à la variable 'href' le segment -->
            @php
            $href .= "/".$segment;

            @endphp

            <!-- ...si le segment est le dernier contenu dans le tableau -->
            @if ($loop->last)

            <!-- ...on affiche cette ligne dessous -->
            <li class="breadcrumb__item"><a href="{{ $href }}" class="breadcrumb__link breadcrumb__link--active">{{ $segment }}</a></li>

            @else

            <!-- ...sinon on affiche cette ligne dessous -->
            <li class="breadcrumb__item"><a href="{{$href}}" class="breadcrumb__link">{{ $segment }}</a></li>
            @endif
            @endforeach
        </ul>
    </nav>
   {{-- @endif --}}