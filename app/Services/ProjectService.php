<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ProjectService
{

    public $user;
    public $content;
    public $thumbnail;

    public function transformBase64ToUrl($user, $content, $project_identifier)
    {

        libxml_use_internal_errors(true);

        //je crée un nouveau document
        $dom = new \DomDocument();
        $dom->encoding = 'utf-8';
        //je charge les données en précisant de désactiver l'ajout automatique d'éléments implicites html / body  (LIBXML_HTML_NOIMPLIED) et d'empêcher l'ajout d'un doctype par défaut lorsqu'il n'est pas trouvé.
        $dom->loadHTML(utf8_decode($content), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        //supprime les espaces.
        $dom->preserveWhiteSpace = false;

        // je viens stocker l'image dans une variable
        $images = $dom->getElementsByTagName('img');
        //je prépare un tableaux vide qui stockera les liens que je vais lui même stocker en variable

        // dd($images);
        //  pour chaque <img> dans le champs summernote
        foreach ($images as $key => $image) {
            // je récupère l'attribut 'src' que je vais stoker dans la variable
            $data = $image->getAttribute('src');

            //si la source de l'image est 'data-url'
            if (preg_match('/data:image/', $data)) {
                // je récupère le mimetype
                preg_match('/data:image\/(?<mime>.*?)\;/', $data, $groups);
                $mimeType = $groups['mime'];

                $imageName = gmdate('d_m_Y_') . uniqid() . '.' . $mimeType;
                // je stocke le pseudonyme de l'utilisateur dans la variable
                $userFolder = $user->username;

                $project_folder = '/projet_' . $project_identifier;

                //je stoke le chemin du dossier que je vais créer par la suite dans une variable
                $storagePath = '/projets/' . $userFolder . $project_folder;

                $path =  $storagePath . '/' . $imageName;

                //si le dossier n'existe pas, je le crée
                if (!Storage::exists($storagePath)) {

                    // je convertis la  base64 en image 
                    $img = Image::make($data)
                        ->encode($mimeType, 100);

                    // je la télécharge dans le disque public  
                    Storage::disk('public')->put($path, $img);
                }


                //je retire l'attribut à l'image...
                $image->removeAttribute('src');
                //...et je lui attribue le nouvel attribut
                $image->setAttribute('src', "/storage/{$path}");
            }
        }

        $projectContent = $dom->saveHTML();


        return $projectContent;
    }


    public function uploadThumbnailToUserProjectFolder($project, $user, $thumbnail)
    {

        //je recupère l'username de l'utilisateur que je stocke en variable
        $userFolder = $user->username;
        //je recupère l'identifiant du projet pour constitué un dossier pour ce dit projet que je stockerai en variable
        $project_identifier = '/projet_' . $project->id_number;
        //je crée le chemin du dossier dans lequel l'image sera stocker.
        $storagePath = 'projets/' . $userFolder . $project_identifier . '/thumbnail';
        // si il existe l'ancien thumbnail du projet
        if ($project->thumbnail) {
            //je le supprime
            Storage::delete('public/' . $storagePath . '/' . $project->thumbnail);
        }
        //si le dossier n'existe pas,...
        if (!Storage::exists($storagePath)) {
            // ... je récupère mon image que je vais stoker dans le dossier avatars/[nom-de-l'utilisateur] dans le storage local 'public/'
            $storagePath;
        }
        //je crée un nouveau nom pour le thumbnail
        $new_name = 'thumbnail_' . gmdate('d_m_Y_') . uniqid()  . '.' . $thumbnail->getClientOriginalExtension();
        //je le déplace dans le dossier storage, avec son nouveau nom
        $thumbnail->move(public_path("/storage/{$storagePath}"), $new_name);

        // j'enregistre le thumbnail du projet sous son nouveau nom en BDD
        $project->thumbnail = $new_name;
        //je viens stocker ce thumnail en variable...
        $projectThumb = $project->thumbnail;
        //...pour le retourner
        return $projectThumb;
    }
}
