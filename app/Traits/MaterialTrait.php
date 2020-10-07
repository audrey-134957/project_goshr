<?php

namespace App\Traits;

use Illuminate\Http\Request;

use App\Models\Material;


trait MaterialTrait
{

    public function editOrCreateMaterial($project, Request $request)
    {
        //pour une question de sécurité, je recherche l'utilisateur connecté

        // // je récupère tous les matériels du projet
        $materials = $project->materials()->get();

        //s'il existe des matériels pour ce projet
        if (isset($materials)) {

            // pour chaque matériel
            foreach ($materials as $project_material) {


                // je récupère chaque champ ayant pour nom d'input 'material' en récupérant au passage l'id du matériel que je vais stocker dans la variable name;
                $name = $request->input('material')[$project_material->id];

                //si le champs du matériel ayant pour id X est null      
                if (is_null($name)) {
                    //je supprime le matériel concerné
                    $project_material->delete();
                    //autrement
                } else {
                    //j'édite le matériel
                    $project_material->update([
                        'name' => $name
                    ]);
                }
            }
        }

        //s'il y a aucune collection matériel pour le projet
        if ($project->materials->count()  === 0) {
            //je récupère la valeur de l'input matériel
            $materials = $request->material;
            if ($request->has('material')) {

                //pour chaque matériel
                foreach ($materials as $material) {

                    //si le materiel n'a pas de valeur null
                    if ($material !== null) {

                        //je crée mon matériel
                         $project_material =  Material::create([
                               'name' => $material,
                               'project_id' => $project->id
                           ]);
                    }

                }

            }

        }

        //par défaut, si j'appuie sur le bouton "ajouter du matériel" et si le nouveau champs ayant pour nom 'new_material' existe
        if ($request->has('new_material')) {
            $newMatInput = $request->input('new_material');
            //pour chacun de ces inputs
            foreach ($newMatInput as $newMaterial) {
                // s'il y a du nouveau matériel dans le champs
                if ($newMaterial) {
                    //je le crée
                      $project_material = Material::create([
                           'name' => $newMaterial,
                           'project_id' => $project->id
                       ]);
                }
            }
        }


        // return $project_material;
    }
}
