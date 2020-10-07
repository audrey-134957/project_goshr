<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Comment;

use App\Http\Requests\EditComment;
use App\Http\Requests\EditCommentReply;
use App\Http\Requests\StoreComment;
use App\Http\Requests\StoreCommentReply;
use App\Notifications\NewCommentPosted;
use App\Notifications\NewCommentReplyPosted;
use App\Notifications\SendMailToAuthorConcerningCommentDeletion;

class CommentController extends Controller
{

    /**
     * Store a new created comment in database.
     *
     * @param  \Illuminate\Http\Requests\StoreComment $request
     * @param int $project | id the project
     * @param string $slug | slug of the project
     * @return \Illuminate\Http\Response
     */
    public function store(StoreComment $request, $project, $slug)
    {
        //j'autorise l'utilisateur connecté et non admin à pouvoir créer un commentaire.
        $this->authorize('create', Comment::class);

        //je récupère le projet
        $project = Project::where('status_id', 2)->findOrFail($project);
        //je récupère le slug du project publié en variable
        $slug = $project->slug;

        /* Store new comment */
        $comment = new Comment();
        $comment->content = purifier($request->comment_content);
        $comment->user_id = auth()->user()->id;
        $project->comments()->save($comment);

        //s le commentaire n'a pas été sauvé
        if (!$project->comments()->save($comment)) {
            //je redirige le projet vers la page de projet avec un message d'erreur
            return redirect()->route('projects.show', [
                'project' => $project,
                'slug' => $slug
            ])->with('error', "Une erreur s'est produite lors de l'ajout du commentaire.");
        }
        //je notifie l'auteur du projet qu'un commentaire vient d'être posté
        if ($comment->user_id !== $project->user_id) {
            //l'auteur du projet est notifié
            $project->user->notify(new NewCommentPosted($comment, $project, auth()->user()));
        }
        //je redirige l'utilisateur vers le projet publié
        return redirect()->route('projects.show', [
            'project' => $project,
            'slug' => $slug
        ]);
    }

    /**
     * Update the comment in database.
     *
     * @param  \Illuminate\Http\Requests\EditComment  $request
     * @param int $project | id the project
     * @param string $slug | slug of the project
     * @param int $comment | id the comment
     * @return \Illuminate\Http\Response
     */
    public function update(EditComment $request, $project, $slug, $comment)
    {
        //je stocke le projet concerné en variable
        $project = Project::where('status_id', 2)->findOrFail($project);
        //je stocke le slug du projet concerné
        $slug = $project->slug;

        /* Edit comment */
        $comment = Comment::findOrFail($comment);

        //j'autorise l'utilisateur connecté et non admin à pouvoir  éditer son commentaire.
        $this->authorize('update', $comment);

        $comment->content = purifier($request->edit_comment_content);
        $comment->save();
        //si le commentaire n'a pas été sauvé
        if (!$comment->save()) {
            //je redirige l'utilisateur vers la page du projet avec un message d'erreur
            return redirect()->route('projects.show', [
                'project' => $project,
                'slug' => $slug
            ])->with('error', "Une erreur s'est produite lors de l'édition du commentaire.");
        }

        //je redirige l'utilisateur vers le projet publié
        return redirect()->route('projects.show', [
            'project' => $project,
            'slug' => $slug
        ]);
    }

    /**
     * Store the comment reply in database.
     *
     * @param  \Illuminate\Http\Requests\StoreCommentReply $request
     * @param int $project | id the project
     * @param string $slug | slug of the project
     * @param int $comment | id the comment
     * @return \Illuminate\Http\Response
     */
    public function storeReply(StoreCommentReply $request, $project, $slug, $comment)
    {
        //j'autorise l'utilisateur connecté et non admin à pouvoir créer un commentaire.
        $this->authorize('create', Comment::class);

        $comment = Comment::findOrFail($comment);
        //je récupère le projet
        $project = Project::where('status_id', '=', 2)->findOrFail($project);
        //je récupère le slug du project publié en variable
        $slug = $project->slug;

        /* Store comment reply */
        $commentReply = new Comment();
        $commentReply->content = purifier($request->comment_reply_content);
        $commentReply->user_id = auth()->user()->id;
        $comment->comments()->save($commentReply);

        //si le commentaire n'a pas été sauvé
        if (!$comment->comments()->save($commentReply)) {
            //je redirige l'utilisateur vers la page du projet avec un message d'erreur
            return redirect()->route('projects.show', [
                'project' => $project,
                'slug' => $slug
            ])->with('error', "Une erreur s'est produite lors de l'ajout du commentaire.");
        }

        //je récupère le dernier commentaire
        $lastReplyComment = $comment->comments()->orderBy('created_at', 'desc')->first();
        //si l'auteur de ce dernier commentaire est le même que l'auteur du projet
        if ($lastReplyComment->user_id === $project->user_id) {
            //je notifie l'auteur du commentaire
            $comment->user->notify(new NewCommentReplyPosted($commentReply, $project, auth()->user()));
        }
        //si l'auteur du dernier commentaire est différent de celui de l'auteur du projet
        if ($lastReplyComment->user_id !== $project->user_id) {
            //je notifie l'auuteur du projet
            $project->user->notify(new NewCommentReplyPosted($commentReply, $project, auth()->user()));
        }

        //je redirige l'utilisateur vers le projet publié
        return redirect()->route('projects.show', [
            'project' => $project,
            'slug' => $slug
        ]);
    }


    /**
     * Update the comment reply in database.
     *
     * @param  \Illuminate\Http\Requests\EditCommentReply  $request
     * @param int $project | id the project
     * @param string $slug | slug of the project
     * @param int $comment | id the comment
     * @return \Illuminate\Http\Response
     */
    public function updateReply(EditCommentReply $request, $project, $slug, $comment)
    {

        //je stocke le projet concerné en variable
        $project = Project::where('status_id', '=', 2)->findOrFail($project);
        //je stocke le slug du projet concerné
        $slug = $project->slug;
        //je retrouve le commentaire
        $commentReply = Comment::findOrFail($comment);
        //j'autorise l'utilisateur connecté et non admin à pouvoir éditer son commentaire.
        $this->authorize('update', $commentReply);
        //j'edite le contenu
        $commentReply->content = purifier($request->edit_comment_reply_content);
        $commentReply->save();

        //si ce commentaire n'a pas été édité
        if (!$commentReply->save()) {
            //je redirige l'administrateur vers la page du projet avec un message d'erreur.
            return redirect()->route('projects.show', [
                'project' => $project,
                'slug' => $slug
            ])->with('error', "Une erreur s'est produite lors de l'édition du commentaire.");
        }


        //je redirige l'utilisateur vers le projet publié
        return redirect()->route('projects.show', [
            'project' => $project,
            'slug' => $slug
        ]);
    }

    /*********** Super Admin ***********/

    /**
     * Update the comment reply in database.
     *
     * @param int $admin | id of the admin
     * @param int $project | id of the project
     * @param string $slug | slug of the project
     * @param int $comment | id of the comment
     * @return \Illuminate\Http\Response
     */
    public function adminDeleteComment($admin, $project, $slug, $comment)
    {
        //je recherche le commentaire.
        $comment = Comment::findOrFail($comment);

        //j'autorise l'utilisateur admin  connecté à pouvoir supprimer le commentaire.
        $this->authorize('delete', $comment);
        //je recherche le projet concerné
        $project = Project::findOrFail($project);
        //je récupère le slug du projet
        $slug = $project->slug;
        //je récupère l'auteur du commentaire
        $commentAuthor = $comment->user;
        //si le commentaire n'existe pas,
        if (!$comment->exists()) {
            //je redirige l'administrateur vers la page du projet avec un message d'erreur
            return redirect()->route('admin.showProject', [
                'adminId' => auth()->user()->id,
                'project' => $project,
                'slug' => $slug,
            ])->with('error', "Le commentaire n'existe pas.");
        }

        //autrement, je supprime le commentaire
        $comment->delete();

        //si le commentaire est trouvé
        if ($comment->exists()) {
            //je redirige l'administrateur vers la page du projet avec un message d'erreur
            return redirect()->route('admin.showProject', [
                'adminId' => auth()->user()->id,
                'project' => $project,
                'slug' => $slug,
            ])->with('status', "Une erreur s'est produite lors de la suppression du commentaire.");
        }
        //autrement, je notifie l'auteur du commentaire que celui-ci a été supprimé
        $commentAuthor->notify(new SendMailToAuthorConcerningCommentDeletion($comment, $project, $commentAuthor));

        //je redirige l'administrateur vers la page du projet avec un message de confirmation
        return redirect()->route('admin.showProject', [
            'adminId' => auth()->user()->id,
            'project' => $project,
            'slug' => $slug,
        ])->with('status', 'Le commentaire a bien été supprimé.');
    }
}
