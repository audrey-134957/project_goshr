<?php

namespace App\Http\Controllers;

use App\Models\Project;

class HomeController extends Controller
{

    /**
     * Display the home and a listing of the last projects.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //je récuềre les projets
        $projects = Project::with('category', 'user', 'materials', 'difficulty_level', 'unity_of_measurement','status')
                            ->where('status_id', 2)
                            ->orderBy('created_at', 'desc')
                            ->get();



        return view('home.index', ['projects' => $projects]);
    }
}
