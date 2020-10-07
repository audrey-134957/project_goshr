<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait ReportTrait
{

    protected $content;

    public function __construct($content){

        if()
        $this->content = $content;
    }



    public function reportContent(Request $request, $content)
    {
        // $this->authorize('create', $report);

        $request->validate([
            'motives' => 'required|array|min:1',
            'motives.*' => 'required|integer|exists:motives,id'
        ]);


        $user = auth()->user();

        //si l'utilisateur n'a fait aucun signalement
        if ($user->reports->count() == 0) {

            //je viens ajouter l'identifiant de l'utilisateur à la table pivot 'reportable'
            $data = collect($request->motives)->mapWithKeys(function ($id) {
                return [$id => ['user_id' => auth()->user()->id]];
            })->toArray();

            $content->reports()->attach($data);

            //je redirige l'utilisateur
            return redirect()->back()->with('status', 'Le projet a été signalé.');
        }
        return redirect()->back()->with('error', 'Vous avez déjà signalé ce projet.');
    }
}
