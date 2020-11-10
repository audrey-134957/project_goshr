<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($project) {

            $tokenProject = bcrypt(Str::random(60));
            $tokenProjectDraft = bcrypt(Str::random(60));

            session(['project_identifier' => $project->id_number]);
            $identifier = session('project_identifier');

            $storagePath = 'public/projets/' . $project->user->username . '/projet_' . $identifier . '/thumbnail';


            if (!Storage::exists($storagePath)) {
            //     // ... je récupère mon image que je vais stoker dans le dossier avatars/[nom-de-l'utilisateur] dans le storage local 'public/'
                Storage::makeDirectory($storagePath);
            }

            /* Create new bank of tokens */

            $bOT = new BankOfToken();

            $bOT->token_project = str_replace('/', '$', $tokenProject);
            $bOT->token_project_draft = str_replace('/', '$', $tokenProjectDraft);
            $bOT->user_id = $project->user_id;
            $bOT->save();
        });

        static::saving(function ($project) {
            //     $project->user_id = auth()->user()->id;
            $project->slug = Str::slug($project->title);
        });

        self::updating(function ($project) {

            //je vérifie si le contenu du projet a été modifié. ( est -ce que ce contenu est différent ?)
            if ($project->content != $project->getOriginal('content')) {
                //je constitue le dossier de l'utilisateur
                $userFolder = $project->user->username;
                //je crée le dossier du thumbnail
                $project_folder = '/projet_' . $project->id_number;
                //je stoke le chemin du dossier que je vais créer par la suite dans une variable
                $storagePath = '/public/projets/' . $userFolder . $project_folder . '/';
                //je stocke tous les fichiers dossier dans une variable
                $files = Storage::files($storagePath);
                //je récupère la liste d'images qui sont présents dans ce projet
                // j'utilise l'expression régulière '/src="([^"]+)"/' . On souhaite un 'src='  suivi de '""'( guillemets) dans lequel on aura une chaine de caractères qui ne contient pas de '""' (guillemets), répété plusieurs fois.
                // on tente de savoir combien de fois nous avons cette expression dans le contenu du projet.
                //on va sauvegarder ces correspondances dans la variable '$matches'.
                //si on trouve plusieurs fois cette occurence, ...
                if (preg_match_all('/src="([^"]+)"/', $project->content, $matches) > 0) {
                    //...il y a donc bien plusieurs images
                    //
                    $images = array_map(function ($match) {
                        return basename($match);
                    }, $matches[1]);
                    // dd($images);
                    //pour chaque fichiers contenus dans le dossier du projet
                    foreach ($files as $file) {
                        //je ne récupère que le nom de l'image que je stocke en variable
                        $imageName = substr(strrchr($file, "/"), 1);
                        //si cette image ne se retrouve pas dans le tableaux d'images contenus dans le contenu du projet en BDD
                        if (!in_array($imageName, $images)) {
                            //je la supprimer du dossier du projet
                            Storage::delete($storagePath . $imageName);
                        } else {
                            // dump("$test trouvé!");
                        }
                    }
                } else {
                    //si on en trouve aucune, on devra supprimer tous les fichiers qui n'ont aucune correspondance.
                    $files =  Storage::files($storagePath);
                    Storage::delete($files);
                }
            }
        });

        self::deleting(function ($project) {

            $userFolder = $project->user->username;
            //je crée le dossier du thumbnail
            $project_folder = '/projet_' . $project->id_number;
            //je stoke le chemin du dossier que je vais créer par la suite dans une variable
            $storagePath = 'public/projets/' . $userFolder . $project_folder;
            Storage::deleteDirectory($storagePath);
        });


        // self::deleted(function ($project) {
        //     $user = $project->user;

        //     if ($user->projects->count() === 0) {
        //         $user->bank_of_token->update([
        //             'token_project'         => null,
        //             'token_project_draft'   => null
        //         ]);
        //     }
        // });
    }

    // un projet appartient à un utilisateur.
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    //un projet appartient à une catégorie
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    //un projet a plusieurs matériels
    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    //à ce projet est associé une unité de mesure
    public function unity_of_measurement()
    {
        return $this->hasOne(UnityOfMeasurement::class, 'id', 'unity_of_measurement_id');
    }

    //à ce projet est associé un niveau de difficulté.
    public function difficulty_level()
    {
        return $this->hasOne(DifficultyLevel::class, 'id', 'difficulty_level_id');
    }

    //ce projet pourra avoir plusieurs commentaires.
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    // un projet a un statut ( publié / brouillon )
    public function status()
    {
        return $this->hasOne(Status::class, 'id', 'status_id');
    }

    public function topics()
    {
        return $this->morphMany(Topic::class, 'topicable');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function getThumbnail(Project $project)
    {
        //je cherche l'utilisateur
        $project = Project::findOrFail($project->id);
        $user = $project->user;
        //si un utilisateur n'a pas son avatar de téléchargé, dans ce cas, on lui affiche une image pas défault
        $thumbnailPath = $this->thumbnail;
        $userFolder = $user->username;
        $project_identifier = '/projet_' . $project->id_number;

        // $storagePath = 'projets/' . $userFolder . '/thumbnail/';
        $storagePath = 'projets/' . $userFolder . $project_identifier . '/thumbnail/';

        // je retourne l'avatar de l'utilisateur
        return  "/storage/" . $storagePath . $thumbnailPath;
    }

    /* Durée du projet */

    public function getDuration()
    {
        $duration_number = $this->duration;
        $unity = $this->unity_of_measurement->name;
        $duration = $duration_number . ' ' . $unity;

        return $duration;
    }

    /* commentaires du projet */

    public function getCommentsBoxTitle()
    {

        if ($this->comments()->count() <= 1) {
            $commentBoxTitle = 'commentaire';
        } else {
            $commentBoxTitle = 'commentaires';
        }

        return $commentBoxTitle;
    }

    /* topics du projet */

    public function getTopicsBoxTitle()
    {
        if ($this->topics()->count() <= 1) {
            $topicBoxTitle = 'question';
        } else {
            $topicBoxTitle = 'questions';
        }

        return $topicBoxTitle;
    }
}
