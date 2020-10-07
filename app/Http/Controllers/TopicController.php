<?php

namespace App\Http\Controllers;


use App\Models\Topic;
use App\Models\Project;

use App\Http\Requests\EditTopic;
use App\Http\Requests\EditTopicReply;
use App\Http\Requests\StoreTopic;
use App\Http\Requests\StoreTopicReply;
use App\Notifications\NewTopicReplyPosted;


use App\Traits\ProjectTrait;

use App\Notifications\NewTopicPosted;
use App\Notifications\SendMailToAuthorConcerningTopicDeletion;
use App\Notifications\SendMailToUserToNotifyTopicDeletion;

class TopicController extends Controller
{

    use ProjectTrait;


    /**
     * Store a new created topic in database.
     *
     * @param  \Illuminate\Http\Requests\StoreTopic  $request
     * @param string $project | id of the project
     * @param string $slug | slug of the project
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTopic $request, $project, $slug)
    {
        //j'autorise l'utilisateur connecté à créer un topic
        $this->authorize('create', Topic::class);
        //je récupère le projet
        $project = Project::where('status_id', 2)->findOrFail($project);
        //je récupère le slug du project publié en variable
        $slug = $project->slug;
        //je crée une nouvelle instance de topicaire
        $topic = new Topic();
        //je recupère la valeur de l'input que je stocke dans une variable
        $topic->content = purifier($request->topic_content);
        //je récupère l'id de l'utilisateur connecté
        // $topic->user_id = auth()->user()->id;
        //je sauve le topicaire
        $project->topics()->save($topic);

        //si le topic n'a pas été sauvé
        if (!$project->topics()->save($topic)) {

            //je redirige l'utilisateur vers la page d'erreur avec un message d'erreur
            return redirect()->route('projects.show', [
                'project' => $project,
                'slug'    => $slug
            ])->with('error', "Une erreur s'est produite lors de l'ajout du topic.");
        }
        //je notifie l'auteur du projet qu'un topicaire vient d'être posté
        //la notification n'est envoyé que si l'auteur du topicaire n'est pas l'auteur du projet
        if ($topic->user_id !== $project->user_id) {
            /**
             * $project = projet sur lequel le topic est posté
             * $topic = topic qui vient d'être posté
             * $project->user = auteur du projet
             */
            $project->user->notify(new NewTopicPosted($project, $topic, $topic->user));
        }

        //je redirige l'utilisateur vers le projet publié
        return redirect()->route('projects.show', [
            'project' => $project,
            'slug'    => $slug
        ]);
    }

    /**
     * Update the topic in database.
     * 
     * @param  \Illuminate\Http\Requests\EditTopic  $request
     * @param string $project | id of project
     * @param string $slug | slug of project
     * @param int $topic | id of the topic
     * @return \Illuminate\Http\Response
     */
    public function update(EditTopic $request, $project, $slug, $topic)
    {
        //je stocke le projet concerné en variable
        $project = Project::where('status_id', 2)->findOrFail($project);
        //je stocke le slug du projet concerné
        $slug = $project->slug;
        //je stocke le topicaire en variable
        $topic = Topic::findOrFail($topic);
        //j'autorise l'auteur du topic ) éditer son topic
        $this->authorize('update', $topic);

        // j'édite le contenu du projet
        $topic->content = purifier($request->edit_topic_content);
        $topic->save();
        //si le topic n'a pas été sauvé
        if (!$topic->save()) {
            //je redirige l'utilisateur vers la page du projet avec un message d'erreur
            return redirect()->route('projects.show', [
                'project' => $project,
                'slug'     => $slug
            ])->with('error', "Une erreur s'est produite lors de l'édition du topic.");
        }
        //je redirige l'utilisateur vers le projet publié
        return redirect()->route('projects.show', [
            'project' => $project,
            'slug'     => $slug
        ]);
    }


    /**
     * Store a new created topic reply in database.
     *
     * @param  \Illuminate\Http\Requests\StoreTopicReply  $request
     * @param string $project | id of the project
     * @param string $slug | slug of the project
     * @param int $topic | id of the topic
     * @return \Illuminate\Http\Response
     */
    public function storeReply(StoreTopicReply $request, $project, $slug, $topic)
    {

        $topic = Topic::findOrFail($topic);
        //je récupère le projet
        $project = Project::where('status_id', 2)->findOrFail($project);
        //je récupère le slug du project publié en variable
        $slug = $project->slug;
        //je crée une nouvelle instance de commentaire
        $topicReply = new Topic();
        //je recupère la valeur de l'input que je stocke dans une variable
        $topicReply->content = purifier($request->topic_reply_content);
        //je récupère l'id de l'utilisateur connecté
        $topicReply->user_id = auth()->user()->id;
        //je sauve le commentaire
        $topic->topics()->save($topicReply);
        //j'autorise l'utilisateur connecté à créer un topic
        $this->authorize('create', Topic::class);
        //si le topic n'a pas été sauvé
        if (!$topic->topics()->save($topicReply)) {
            //je redirige l'utilisateur vers la page du projet avec un message d'erreur
            return redirect()->route('projects.show', [
                'project' => $project,
                'slug'     => $slug
            ])->with('error', "Une erreur s'est produite lors de l'ajout du topic.");
        }

        $lastReplyTopic = $topic->topics()->orderBy('created_at', 'desc')->first();

        //si l'auteur répond à ma question, je suis notifié,
        //si je répond à sa réponse, il est notifié
        if ($lastReplyTopic->user_id === $project->user_id) {
            $topic->user->notify(new NewTopicReplyPosted($topicReply, $project, auth()->user()));
        }
        if ($lastReplyTopic->user_id !== $project->user_id) {
            $project->user->notify(new NewTopicReplyPosted($topicReply, $project, auth()->user()));
        }

        //je redirige l'utilisateur vers le projet publié
        return redirect()->route('projects.show', [
            'project' => $project,
            'slug'     => $slug
        ]);
    }


    /**
     * Update the topic reply in database.
     * 
     * @param  \Illuminate\Http\Requests\EditTopicReply  $request
     * @param string $project id of project
     * @param string $slug slug of project
     * @param int $topic id of the topic
     * @return \Illuminate\Http\Response
     */
    public function updateReply(EditTopicReply $request, $project, $slug, $topic)
    {
        //je stocke le projet concerné en variable
        $project = Project::where('status_id', 2)->findOrFail($project);
        //je stocke le slug du projet concerné
        $slug = $project->slug;
        //je stocke le commentaire du commentaire en variable
        $topicReply = Topic::findOrFail($topic);
        //j'autorise l'utilisateur connecté et non admin à pouvoir éditer son topic.
        $this->authorize('update', $topicReply);
        //je récupère la valeur du textarea que je stocke dans le contenu de la variable
        $topicReply->content = purifier($request->edit_topic_reply_content);
        //j'édite le contenu du projet
        $topicReply->save();
        //je redirige l'utilisateur vers le projet publié
        return redirect()->route('projects.show',  [
            'project' => $project,
            'slug'     => $slug
        ]);
    }




    /*********** Super Admin ***********/


    public function adminDeleteTopic($admin, $project, $slug, $topic)
    {

        //je retrouve le topic
        $topic = Topic::findOrFail($topic);
        //j'autorise l'utilisateur admin  connecté à pouvoir supprimer le commentaire.
        $this->authorize('delete', $topic);
        //je retrouve le projet
        $project = Project::findOrFail($project);
        //je recupère le slug du projet
        $slug = $project->slug;
        //je récupère l'auteru du topic
        $topicAuthor = $topic->user;
        //si le topic n'existe pas
        if (!$topic->exists()) {
            //je redirige l'administrateur vers la page du projet avec un message d'erreur
            return redirect()->route('admin.showProject', [
                'adminId' => auth()->user()->id,
                'project' => $project,
                'slug' => $slug,
            ])->with('error', "Le topic n'existe pas.");
        }
        //je supprime le topic
        $topic->delete();
        //si le topic existe toujours après la supposée suppression
        if ($topic->exists()) {
            //je redirige l'administrateur vers la page du projet avec un message d'erreur
            return redirect()->route('admin.showProject', [
                'adminId' => auth()->user()->id,
                'project' => $project,
                'slug' => $slug,
            ])->with('status', "Une erreur s'est produite lors de la suppression du topic.");
        }
        //autrement, je notifie l'auteur du topic que celui-ci a été supprimé
        $topicAuthor->notify(new SendMailToAuthorConcerningTopicDeletion($topic, $project, $topicAuthor));

        //je redirige l'administrateur vers la page du projet avec un message de confirmation
        return redirect()->route('admin.showProject', [
            'adminId' => auth()->user()->id,
            'project' => $project,
            'slug' => $slug,
        ])->with('status', 'Le topic a bien été supprimé.');
    }
}
