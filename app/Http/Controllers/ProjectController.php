<?php

namespace App\Http\Controllers;

use App\Services\ProjectService;

use App\Models\Category;
use App\Models\DifficultyLevel;
use App\Http\Requests\EditProject;
use App\Http\Requests\StoreProject;
use App\Models\Material;
use App\Models\Motive;
use App\Notifications\SendMailToAuthorConcerningProjectDeletion;
use App\Models\Project;
use App\Models\UnityOfMeasurement;
use App\Models\User;
use App\Traits\ProjectTrait;
use App\Traits\MaterialTrait;

class ProjectController extends Controller
{

    use ProjectTrait, MaterialTrait;

    /**
     * Display the projects.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //je récupère l'utilisateur connecté
        $user = auth()->user();

        //si une catégorie est sélectionnée
        if (request()->category) {
            //je retrouve les projets avec leur catégorie
            $projects =  Project::with('category')
                ->where('status_id', 2)
                ->where('fictionnal_deletion', 0)
                ->whereHas('category', function ($query) {
                    //si le slug correpond à celui selectionné
                    $query->where('slug', request()->category);
                    //je les récupère
                })->orderBy('created_at', 'DESC')
                ->get();
            // je récupère les catégories
            $categories = Category::all();
            //je récupère le nom de la catégorie qui correspond à celle sélectionnée.
            $categoryName = $categories->where('slug', request()->category)->first()->name;
            //si aucune catégorie n'est sélectionnée
        } else {
            //je récupère toutes les catégories
            $categories = Category::get();
            //le nom de catégorie sera null
            $categoryName = '';
            //je récupères tous les projets.
            $projects = Project::with('category', 'user', 'materials', 'difficulty_level', 'unity_of_measurement', 'status')
                ->where('status_id', 2)
                ->where('fictionnal_deletion', 0)
                ->orderBy('created_at', 'DESC')
                ->get();
        }

        return view('projects.index', [
            'user'          => $user,
            'categories'    => $categories,
            'projects'      => $projects,
            'categoryName'  => $categoryName
        ]);
    }


    /**
     * Show the form for creating project.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //j'autorise l'utilisateur non admin à pouvoir créer un projet
        $this->authorize('create', Project::class);
        //je récupère l'utilisateur connecté
        $user = auth()->user();
        //je récupère toutes les catégories
        $categories = Category::get();
        //je récupère tous les niveaux de difficulté
        $difficultyLevels = DifficultyLevel::get();
        //je récupère les unités de mesure de temps
        $unities = UnityOfMeasurement::get();
        return view('projects.create', [
            'user'          => $user,
            'categories'    => $categories,
            'unities'       => $unities,
            'difficultyLevels'  => $difficultyLevels
        ]);
    }

    /**
     * Send the message to receiver.
     *
     * @param  \Illuminate\Http\Requests\StoreProject  $request
     * @param  \Services\ProjectService $projectService
     * @return \Illuminate\Http\Response
     * 
     */
    public function store(StoreProject $request, ProjectService $projectService)
    {

        //j'autorise l'utilisateur non admin à sauver son projet
        $this->authorize('create', Project::class);
        //pour une question de sécurité, je recherche l'utilisateur connecté
        $user = auth()->user();
        // je récupère mon l'input 'content' que je stocke dans une variable pour le passer à mon service 'ProjectSertvice -> transformBase64Url()
        $content = purifier($request->input('content'));
        // je génère un identifiant pour mon projet que je stocke dans une variable pour le passer à mon service 'ProjectSertvice -> transformBase64Url()
        $project_id_number = mt_rand(100000, 999999);
        // je retrouve la catégorie en correspondance avec celle sélectionnée dans le formulaire
        $cat = Category::where('id', $request->category)->firstOrFail('id');
        // je retrouve la difficulté en correspondance avec celle sélectionnée dans le formulaire
        $difficultyLevel = DifficultyLevel::where('id', $request->difficulty_level)->firstOrFail('id');
        //je retrouve l'unité de mesure en correspondance avec celle sélectionnée dans le formulaire
        $unity = UnityOfMeasurement::where('id', $request->unity_of_measurement)->firstOrFail('id');
        //je crée une nouvelle instance du projet en exluant le contenu
        $project = new Project([
            'category_id' => $cat->id, //je donne l'id de la catégorie
            'difficulty_level_id' => $difficultyLevel->id,
            'title' => $request->title,
            'id_number' => $project_id_number,
            'duration' => $request->duration,
            'unity_of_measurement_id' => $unity->id, //je donne l'id de l'unité de mesure
            'budget' => $request->budget,
        ]);

        //je stoke en session l'identifiant du projet
        session(['project_identifier' => $project->id_number]);
        //je fais stoker cet identifiant en variable
        $project_identifier = session('project_identifier');
        //je fait appel au contenu que je vais ajouter dans l'instance projet
        $project->content = purifier($projectService->transformBase64ToUrl($user, $content, $project_identifier));
        //s'il y a une image thumbnail
        if ($request->has('thumbnail')) {
            //je la récupère dans le champs
            $thumbnail = $request->file('thumbnail');
            //j'appel la methode qui se chargera de lui donner un nom et de la stocker
            $projectService->uploadThumbnailToUserProjectFolder($project, $user, $thumbnail);
        }

        //je sauve mon projet
        $project->save();
        //si le projet n'a pas été sauvé
        if (!$project->save()) {
            //je redirige l'utilisateur vers la page de création de projet avec un message d'erreur
            return view('projects.create')->with('error', "Une erreur s'est produite lors de la création du projet.");
        }

        //je récupère le slug du projet
        $slug = $project->slug;

        //je stocke la valeur du/des champs matériel dans une variable
        $materials = $request->material;
        //je boucle sur ce tableau...
        foreach ($materials as $material) {
            //si la valeur n'est pas null
            if ($material !== null) {
                //pour récupérer et créer indépendamment chaque matérial saisie pour le projet
                Material::create([
                    'name' => $material,
                    'project_id' => $project->id
                ]);
            }
        }


        //si le bouton est celui du brouillon
        if ($request->input('submit') == 'draft') {
            //je récupère le token qui me servira a modifier le projet brouillon
            $token = auth()->user()->bank_of_token->token_project_draft;
            //je redirige vers la vue du brouillon avec un status de confirmation
            return redirect()->route('projects.draft', [
                'project'  => $project,
                'slug'     => $slug,
                'token'    => $token
            ])->with('status', 'ton projet a été sauvé comme brouillon !');
        }

        //si le bouton est celui de la publication
        if ($request->input('submit') == 'publish') {
            //je change le status pour celui 'publié'
            $project->update([
                'status_id' => 2
            ]);
            //je redirige mon utilisateur vers la page du projet avec un status de confirmation
            return redirect()->route('projects.show', [
                'project' => $project,
                'slug'    => $slug
            ])->with('status', 'ton projet est à présent publié!');
        }
    }

    /**
     * Show the profile.
     *
     * @param string $project | id of the project
     * @return \Illuminate\Http\Response
     */
    public function show($project)
    {

        //je cherche le projet que je vais stoker en variable
        $project = Project::with('materials')
            ->where('status_id', 2)
            ->where('fictionnal_deletion', 0)
            ->findOrFail($project);


        //je récupère le slug du projet que je stocke en variable
        $slug = $project->slug;
        //je récupère l'auteur du projet
        $user = $project->user;
        //je récupère tous les matériels
        $materials = $project->materials->all();
        //je récupère les motifs
        $motives = Motive::all();
        //je récupère le token qui me permettra d'éditer mon projet brouillon
        $token = $project->user->bank_of_token->token_project_draft;

        // je récupère les commentaire de ce projet
        $comments = $project->comments()
            ->where('fictionnal_deletion', 0)
            ->get();

        $topics = $project->topics()
            ->where('fictionnal_deletion', 0)
            ->get();

        //je récupère les projets hormis celui actuel qui seront en suggestion
        $projects =  Project::with('category', 'user', 'materials', 'difficulty_level', 'unity_of_measurement')
            ->where('status_id', 2)
            ->where('fictionnal_deletion', 0)
            ->whereNotIn('id', [$project->id])
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view(
            'projects.show',
            [
                'user' => $user,
                'project' => $project,
                'materials' => $materials,
                'projects' => $projects,
                'comments' => $comments,
                'topics' => $topics,
                'slug' => $slug,
                'token' => $token,
                'motives' => $motives
            ]
        );
    }

    /**
     * Show the drafted project edition form
     *
     * @param int $project | id of the project
     * @param string $slug | slug of the project
     * @param string $token | token of the drafted project
     * @return \Illuminate\Http\Response
     */
    public function draft($project, $slug, $token)
    {
        //je stocke mon projet brouillon en variable
        $project = Project::where('status_id', 1)->where('fictionnal_deletion', 0)
            ->findOrFail($project);
        //j'autorise l'auteur du projet a éditer son projet.
        $this->authorize('update', $project);
        //je récupère l'utilisateur connecté
        $user = auth()->user();
        //je stocke le slug du projet en variable
        $slug = $project->slug;

        $token = $project->user->bank_of_token->token_project_draft;

        //je stocke la barre code du projet brouillon dans une variable
        //je récupère toutes les catégories
        $categories = Category::get();
        //je récupère tous les matériels
        $materials = $project->materials->all();
        //je récupère tous les niveaux de difficulté
        $difficultyLevels = DifficultyLevel::get();
        //je récupère les unités de mesure de temps
        $unities = UnityOfMeasurement::get();

        $defaultInputsNumber = 1;

        $inputsNumber = count($materials);

        return view('projects.draft', [
            'project' => $project,
            'user' => $user,
            'categories' => $categories,
            'unities' => $unities,
            'difficultyLevels' => $difficultyLevels,
            'defaultInputsNumber' => $defaultInputsNumber,
            'inputsNumber' => $inputsNumber,
            'slug' => $slug,
            'materials' => $materials,
            'token' => $token
        ]);
    }

    /**
     * Update the drafted project in database
     *
     * @param  \Illuminate\Http\Requests\EditProject  $request
     * @param int $project | id of the project
     * @param string $slug | slug of the project
     * @param string $token | token of the drafted project
     * @param  \Services\ProjectService $projectService
     * @return \Illuminate\Http\Response
     */
    public function updateDraft(EditProject $request, $project, $slug, ProjectService $projectService)
    {
        //je récupère le projet brouillon
        $project = Project::where('status_id', 1)->where('fictionnal_deletion', 0)
            ->findOrFail($project);


        //je recupère le slug du projet
        $slug = $project->slug;
        //seule l'auteur du projet pourra modifier son projet brouillon
        $this->authorize('update', $project);
        //je fais appel à la methode 'editProject' qui permettra d'éditer mon contenu
        $this->editProject($project, $request, $projectService);
        //si l'utilisateur clique sur le bouton brouillon
        if ($request->submit === 'draft') {
            //si le status du projet est en publié
            if ($project->status_id == 2) {
                //je le change en brouillon
                $project->update([
                    'status_id' => 1
                ]);
            }
            //je retourne à la dernière page
            return back()->with('status', 'Projet sauvé au brouillon ! Tu pourras le publier en cliquant sur le bouton de publication.');
        }
        //si l'utilisateur clique sur le bouton de publication
        if ($request->submit === 'publish') {
            //j'édite le status du projet en publié
            $project->update([
                'status_id' => 2
            ]);

            //je redirige l'utilisateur vers le projet qu'il vient de publié
            return redirect()->route('projects.show', [
                'project' => $project,
                'slug' => $slug
            ])->with('status', 'ton projet est à présent publié !');
        }
    }

    /**
     * Show the project edition form.
     * @param int $project | id of the project
     * @param string $token | token who will permit to edit the published project
     * @return \Illuminate\Http\Response
     */
    public function edit($project, $token)
    {
        //je récupère l'utilisateur connecté
        $user = auth()->user();
        //je récupère le projet publié que je stocke en variable
        $project = Project::where('status_id', 2)
            ->where('fictionnal_deletion', 0)
            ->findOrFail($project);
        //j'autorise l'auteur du projet a éditer son projet.
        $this->authorize('update', $project);
        //je récupère le slug du projet que je stocke en variable
        $slug = $project->slug;
        //j'autorise uniquement l'auteur du projet à le modifier.
        $this->authorize('edit', $project);
        //je récupère toutes les catégories
        $token = $project->user->token_project;
        //je récupère les categories
        $categories = Category::get();
        //je récupère tous les matériels
        $materials = $project->materials->all();
        //je récupère tous les niveaux de difficulté
        $difficultyLevels = DifficultyLevel::get();
        //je récupère les unités de mesure de temps
        $unities = UnityOfMeasurement::get();
        return view('projects.edit', [
            'project'           => $project,
            'user'              => $user,
            'categories'       => $categories,
            'unities'           => $unities,
            'difficultyLevels'  => $difficultyLevels,
            'slug'              => $slug,
            'materials'         => $materials,
            'token'             => $token
        ]);
    }


    /**
     * Update the published project in database
     * @param  \Illuminate\Http\Requests\EditProject  $request
     * @param int $project | id of the project
     * @param  \Services\ProjectService $projectService
     * @return \Illuminate\Http\Response
     */
    public function update(EditProject $request, $project, ProjectService $projectService)
    {
        //je retrouve le projet publié
        $project = Project::where('status_id', 2)
            ->where('fictionnal_deletion', 0)
            ->findOrFail($project);
        //je récupère le slug du projet
        $slug = $project->slug;
        //seule l'auteur du projet du projet peur modifier celui-ci
        $this->authorize('update', $project);
        //je fais appel à la methode 'editProject' qui permettra d'éditer mon contenu 
        $this->editProject($project, $request, $projectService);
        //si l'utilisateur clique sur le bouton brouillon
        if ($request->input('submit') == 'draft') {
            //je récupère le token qui permettra à l'utilisateur d'éditer son projet brouillon
            $token = $project->user->bank_of_token->token_project_draft;
            //je modifie le status du projet en brouillon
            $project->update([
                'status_id' => 1
            ]);
            //je redirige l'utilisateur sur le projet brouillon
            return redirect()->route('projects.draft', [
                'project'   => $project,
                'slug'      => $slug,
                'token'     => $token
            ])->with('status', 'ton projet a été sauvé comme brouillon !');
        }

        //si l'utilisateur clique sur le bouton de publication
        if ($request->input('submit') == 'publish') {
            //je redirige l'utilisateur sur la vue du projet qu'il vient de publié
            return redirect()->route('projects.show', [
                'project'   => $project,
                'slug'      => $slug
            ])->with('status', 'ton projet est à présent publié !');
        }
    }

    /**
     * Delete the project in database.
     * @param string $project | id of the project
     * @return \Illuminate\Http\Response
     */
    public function destroy($project)
    {
        //je récupère le projet
        $project = Project::findOrFail($project);
        //seule l'auteur du projet peut supprimer ce projet
        $this->authorize('destroy', $project);
        //je supprime le projet

        $project->delete();
        //je redirige l'utililsateur vers la page précédent son action de suppression
        if (strpos(url()->previous(), 'mes-projets') || strpos(url()->previous(), 'mes-brouillons')) {
            return redirect()->back()->with('status', 'Ton projet a bien été supprimé.');
        }

        return redirect()->route('projects.index')->with('status', 'Ton projet a bien été supprimé.');
    }

    /* User Profile */

    /**
     * Show listing of the published projects from author's profile.
     *
     * @param string $user | username of the user
     * @return \Illuminate\Http\Response
     */
    public function listPublishedProjectsFromProfile($user)
    {
        //je récupère l'utilisateur connecté
        $user = User::where('username', $user)->firstOrFail();

        //je recupère les projets de l'utilisateur connecté
        $projects = Project::with('category', 'user', 'materials', 'difficulty_level', 'unity_of_measurement', 'status')
            ->where('status_id', 2)
            ->where('fictionnal_deletion', 0)
            ->where('user_id', $user->id)
            ->get();


        return view('profiles.show', [
            'user'          => $user,
            'projects'      => $projects,
        ]);
    }


    /**
     * Show listing of the drafted projects from author's profile.
     *
     * @param string $user | username of the user
     * @return \Illuminate\Http\Response
     */
    public function listDraftedProjectFromProfile($user)
    {
        //je récupère l'utilisateur connecté
        $user = User::where('username', $user)->firstOrFail();

        //je recupère les projets de l'utilisateur connecté
        $projects = Project::with('category', 'user', 'materials', 'difficulty_level', 'unity_of_measurement', 'status')
            ->where('status_id', 1)
            ->where('fictionnal_deletion', 0)
            ->where('user_id', $user->id)
            ->get();


        return view('profiles.show', [
            'user'          => $user,
            'projects'      => $projects,
        ]);
    }


    /*********** Super Admin ***********/


    /**
     * Display the projects from the admin account.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndexProjects()
    {
        //je récupères tous les projets.
        $projects = Project::with('category', 'user', 'materials', 'difficulty_level', 'unity_of_measurement', 'status')
            ->where('status_id', 2)
            ->where('fictionnal_deletion', 0)
            ->orderBy('created_at', 'DESC')
            ->get();

        if ($projects->count() > 1) {
            $text = 'projets';
        } else {
            $text = 'projet';
        }

        return view('admins.projects.index', [
            'adminId' => auth()->user()->id,
            'projects'      => $projects,
            'text'  => $text
        ]);
    }

    /**
     * Display the projects from the admin account.
     *
     * @param $project | id of the project
     * @param $lug | slug of the project
     * @return \Illuminate\Http\Response
     */
    public function adminShowProject($admin, $project, $slug)
    {
        $project = Project::findOrFail($project);

        $slug = $project->slug;
        $motives = Motive::all();

        return view('admins.projects.show', [
            'project'               => $project,
            'slug'                  => $slug,
            'motives'               => $motives
        ]);
    }


    public function adminDeleteProject($admin, $project)
    {

        //je récupère l'admin connecté
        $admin = auth()->user();

        $project = Project::findOrFail($project);

        $this->authorize('delete', $project);

        $user = $project->user;
        //si le projet n'existe pas
        if (!$project->exists()) {
            //je redirige l'admin vers la page des projets avec un message d'erreur
            return redirect()->route('admin.indexProjects', [
                'adminId' => auth()->user()->id,
            ])->with('error', "Le projet n'existe pas.");
        }

        $project->delete();
        //si le projet n'existe pas
        if ($project->exists()) {
            //je redirige l'admin vers la page des projets avec un message d'erreur
            return redirect()->route('admin.indexProjects', [
                'adminId' => auth()->user()->id,
            ])->with('status', "Une erreur s'est produite lors de la suppression du projet.");
        }
        //je notifie l'auteur du projet que celui a été supprimé
        $user->notify(new SendMailToAuthorConcerningProjectDeletion($user, $project));
        //je redirige l'admin vers la page des projets avec un message d'erreur
        return redirect()->route('admin.indexProjects', [
            'adminId' => auth()->user()->id,
        ])->with('status', 'Le projet a bien été supprimé.');
    }


    public function adminDeleteProjectsSelection()
    {
        $selectedProjects = request()->checkbox;
        //s'il y a une sélection
        if ($selectedProjects) {
            //je recupère tous les projets
            $projects = Project::all();
            //je crée un tableau vide qui me permettra de stoker les 
            $projectsBank = [];
            //pour chaque projet
            foreach ($projects as $project) {
                //je stoke leur clé dans le tableau
                $projectsBank[] = $project->id;
            }
            //pour chaque projet sélectionné
            foreach ($selectedProjects as $selectedProject) {
                //je récupère le projet sélectionné
                $theProject = Project::where('status_id', 2)
                    ->where('fictionnal_deletion', 0)
                    ->where('id', $selectedProject)->firstOrFail();
                //je supprimé le projet sélectionné
                $theProject->delete();
            }

            //
            // $projectsBank = $projectsBank;

            //je stoke les projets qui se retrouvent à la fois dans les projets en BDD et dans les projets sélectionnés
            $selectNotEnymore = array_diff_assoc($projectsBank, $selectedProjects);

            //si les projets ne sont pas trouvés
            if (!$selectNotEnymore) {
                //je redirige l'admin vers la liste des projets avec un message d'erreur
                return redirect()->route('admin.indexProjects', [
                    'adminId' => auth()->user()->id,
                ])->with('status', "Une erreur s'est produite lors de la suppression de la sélection des projets.");
            }

            //autrement je redirige l'admin vers la liste des projets avec un message de confirmation.
            return redirect()->route('admin.indexProjects', [
                'adminId' => auth()->user()->id,
            ])->with('status', 'Les projets sélectionnés ont bien été supprimés.');
        }
    }
}
