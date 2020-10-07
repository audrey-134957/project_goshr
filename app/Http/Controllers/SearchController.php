<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;


class SearchController extends Controller
{

    /**
     * Show the result of the users search.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string'
        ]);

        //je précise que query est la saisie entrée par l'utilisateur
        $q  = $request->q;

        //je récupère les projets qui correpondent étant similaire au titre
        $projects = Project::where('fictionnal_deletion', 0)
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%$q%")
                    // recherche par auteur
                    ->orWhereHas('user', function ($query) use ($q) {
                        $query->where('username', 'like', "%$q%");
                    })
                    //recherche par categorie
                    ->orWhereHas('category', function ($query) use ($q) {
                        $query->where('name', 'like', "%$q%");
                    })
                    //je récupère les projets qui correpondent étant similaire au contenu
                    ->orWhere('content', 'like', "%$q%");
            })
            ->get();


        if ($projects->count() <= 1) {
            $resultsText = 'résultat';
        } else {
            $resultsText = 'résultats';
        }

        return view('searchs.projects.projects-search', [
            'projects' => $projects,
            'resultsText' => $resultsText
        ]);
    }

    /**
     * Show the result of the users search.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchUsers(Request $request)
    {

        $request->validate([
            'q' => 'nullable|string'
        ]);

        //je précise que query est la saisie entrée par l'administrateur
        $q  = request()->q;
        //je récupère les utilisateurs qui correpondent étant similaire à la saisie
        $users = User::with('role')->where('role_id', NULL)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                    ->orWhere('firstname', 'like',  "%$q%")
                    ->orWhere('email', 'like',  "%$q%")
                    ->orWhere('username', 'like',  "%$q%");
            })
            ->orderBy('username', 'asc')
            ->get();

        if ($users->count() > 1) {
            $name = 'utilisateurs';
        } else {
            $name = 'utilisateur';
        }
        return view('admins.searchs.users-search', [
            'users' => $users,
            'name' => $name
        ]);
    }

    public function searchAdmins(Request $request)
    {
        // dd('ok');

        $request->validate([
            'q' => 'nullable|string'
        ]);

        //je précise que query est la saisie entrée par l'administrateur
        $q  = request()->q;
        //je récupère les utilisateurs qui correpondent étant similaire à la saisie
        $users = User::with('role')->where('role_id', NULL)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                    ->orWhere('firstname', 'like',  "%$q%")
                    ->orWhere('email', 'like',  "%$q%")
                    ->orWhere('username', 'like',  "%$q%");
            })
            ->orderBy('username', 'asc')
            ->get();

        if ($users->count() > 1) {
            $name = 'administrateurs';
        } else {
            $name = 'administrateur';
        }
        return view('admins.searchs.admins-search', [
            'users' => $users,
            'name' => $name
        ]);
    }

    /**
     * Show the result of the users search.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchProjects(Request $request)
    {
        $request->validate([
            'q' => 'nullable|string'
        ]);

        //je précise que query est la saisie entrée par l'utilisateur
        $q  = $request->q;

        //je récupère les projets qui correpondent étant similaire au titre
        $projects = Project::where('status_id', 2)
            ->where(function ($query) use ($q) {
                $query->where('title', 'like', "%$q%")
                    ->orWhereHas('user', function ($query) use ($q) {
                        $query->where('username', 'like', "%$q%");
                    })
                    //recherche par categorie
                    ->orWhereHas('category', function ($query) use ($q) {
                        $query->where('name', 'like', "%$q%");
                    })
                    //je récupère les projets qui correpondent étant similaire au contenu
                    ->orWhere('content', 'like', "%$q%");
            })->get();

        if ($projects->count() > 1) {
            $text = 'projets';
        } else {
            $text = 'projet';
        }

        return view('admins.searchs.projects-search', [
            'projects' => $projects,
            'text'     => $text
        ]);
    }
}
