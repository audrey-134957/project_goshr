<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Motive;
use App\Models\Project;
use App\Models\Report;
use App\Models\Topic;
use App\Models\User;

use App\Notifications\SendMailToAuthorConcerningCommentDeletion;
use App\Notifications\SendMailToAuthorConcerningProjectDeletion;
use App\Notifications\SendMailToAuthorConcerningTopicDeletion;

use Carbon\Carbon;

use Illuminate\Http\Request;

class ReportController extends Controller
{


    /**
     * Show the listing of the reports
     */
    public function index()
    {
        if (request()->motive) {

            $reports = Report::with('user', 'reportable', 'motives')
                ->where('read_at', NULL)
                ->whereHas('motives', function ($query) {
                    //si le slug correpond à celui selectionné
                    $query->where('slug', request()->motive);
                    //je les récupère
                })
                ->orderBy('created_at', 'DESC')
                ->get();

            // je récupère les catégories
            $motives = Motive::all();
            //je récupère le nom de la catégorie qui correspond à celle sélectionnée.
            $motiveName = $motives->where('slug', request()->motive)->first()->name;
            //si aucune catégorie n'est sélectionnée
        } else {
            //je récupère toutes les catégories
            $motives = Motive::get();
            //le nom de catégorie sera null
            $motiveName = '';
            //je récupères tous les projets.

            $reports = Report::with('user', 'reportable', 'motives')
                ->where('read_at', NULL)
                ->orderBy('created_at', 'DESC')
                ->get();
        }


        return view('admins.reports.index', [
            'motives' => $motives,
            'motiveName' => $motiveName,
            'reports' => $reports
        ]);
    }


    /**
     * Store the project report
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $project | id of the project
     * @return \Illuminate\Http\Response
     * 
     */
    public function storeProjectReport(Request $request, $project)
    {
        //je récupère le projet
        $project = Project::findOrFail($project);
        //tous les membres sauf l'auteur peuvent créer un report pour ce projet
        $this->authorize('doReport', $project);
        $motivesIds = $request->validate([
            'motives' => 'required|array|min:1',
            'motives.*' => 'required|integer|exists:motives,id'
        ]);

        /* Create new report */
        $report = new Report();
        $project->reports()->save($report);
        foreach ($motivesIds as $motiveId) {
            $report->motives()->attach($motiveId);
        }



        $admins = User::where('role_id', '!=', NULL)->get();



        //je redirige l'utilisateur avec un status de confirmation
        return redirect()->back()->with('status', 'Le projet a été signalé.');
    }

    /**
     * Store the topic report
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $topic | id of the topic
     * @return \Illuminate\Http\Response
     * 
     */
    public function storeTopicReport(Request $request, $topic)
    {
        //je récupère le topic
        $topic = Topic::findOrFail($topic);
        //tous les membres sauf l'auteur peuvent créer un report pour ce topic
        $this->authorize('doReport', $topic);

        $motivesIds = $request->validate([
            'motives' => 'required|array|min:1',
            'motives.*' => 'required|integer|exists:motives,id'
        ]);

        /* Create new report */
        $report = new Report();
        $topic->reports()->save($report);
        foreach ($motivesIds as $motiveId) {
            $report->motives()->attach($motiveId);
        }

        //je redirige l'utilisateur avec un status de confirmation
        return redirect()->back()->with('status', 'Le topic a été signalé.');
    }

    /**
     * Store the topic reply report
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $topic | id of the topic
     * @return \Illuminate\Http\Response
     * 
     */
    public function storeTopicReplyReport(Request $request, $topic)
    {

        //je retrouve le topic
        $topic = Topic::findOrFail($topic);
        //tous les membres sauf l'auteur peuvent créer un report pour ce topic
        $this->authorize('doReport', $topic);

        $motivesIds = $request->validate([
            'motives' => 'required|array|min:1',
            'motives.*' => 'required|integer|exists:motives,id'
        ]);

        /* Create new report */
        $report = new Report();
        $topic->reports()->save($report);
        foreach ($motivesIds as $motiveId) {
            $report->motives()->attach($motiveId);
        }

        //je redirige l'utilisateur avec un status de confirmation
        return redirect()->back()->with('status', 'Le topic a été signalé.');
    }

    /**
     * Store the comment report
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $comment | id of the comment
     * @return \Illuminate\Http\Response
     * 
     */
    public function storeCommentReport(Request $request, $comment)
    {
        //je retrouve le commentaire
        $comment = Comment::findOrFail($comment);

        $motivesIds = $request->validate([
            'motives' => 'required|array|min:1',
            'motives.*' => 'required|integer|exists:motives,id'
        ]);

        /* Create new report */
        $report = new Report();
        $comment->reports()->save($report);
        foreach ($motivesIds as $motiveId) {
            $report->motives()->attach($motiveId);
        }

        //je redirige l'utilisateur avec un status de confirmation
        return redirect()->back()->with('status', 'Le commentaire a été signalé.');
    }

    /**
     * Store the comment reply report
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $comment | id of the comment reply
     * @return \Illuminate\Http\Response
     * 
     */
    public function storeCommentReplyReport(Request $request, $comment)
    {
        //je retrouve le commentaire
        $comment = Comment::findOrFail($comment);

        $motivesIds = $request->validate([
            'motives' => 'required|array|min:1',
            'motives.*' => 'required|integer|exists:motives,id'
        ]);

        /* Create new report */
        $report = new Report();
        $comment->reports()->save($report);
        foreach ($motivesIds as $motiveId) {
            $report->motives()->attach($motiveId);
        }
        //je redirige l'utilisateur avec un status de confirmation
        return redirect()->back()->with('status', 'Le commentaire a été signalé.');
    }




    /*********** Super Admin ***********/


    /**
     * Show the project report ????????????????
     *
     * @param int $admin | id of the authenticate admin
     * @param  int $report | id of the report
     * @param int $project | id of the project
     * @return \Illuminate\Http\Response
     * 
     */
    public function showProjectReport($admin, $report, $project)
    {
        //je récupère le signalement
        $report = Report::findOrFail($report);

        //je récupère le projet
        $project = Project::where('slug', $project)->firstOrFail();

        return view('admins.reports.project.show', [
            'adminId' => auth()->user()->id,
            'report' => $report,
            'project' => $project
        ]);
    }

    /**
     * Show the topic report ????????????????
     *
     * @param int $admin | id of the authenticate admin
     * @param  int $report | id of the report
     * @param int $topic | id of the topi
     * @return \Illuminate\Http\Response
     * 
     */
    public function showTopicReport($admin, $report, $topic)
    {
        //je récupère le signalement
        $report = Report::findOrFail($report);

        //je récupère le topic
        $topic = Topic::findOrFail($topic);

        return view('admins.reports.topic.show', [
            'adminId' => auth()->user()->id,
            'report' => $report,
            'topic' => $topic
        ]);
    }

    /**
     * Show the comment report ????????????????
     *
     * @param int $admin | id of the authenticate admin
     * @param  int $report | id of the report
     * @param int $comment | id of the comment
     * @return \Illuminate\Http\Response
     * 
     */
    public function showCommentReport($admin, $report, $comment)
    {
        //je récupère le signalement
        $report = Report::findOrFail($report);
        //je récupère le commentaire
        $comment = Comment::findOrFail($comment);

        return view('admins.reports.comment.show', [
            'adminId' => auth()->user()->id,
            'report' => $report,
            'comment' => $comment
        ]);
    }

    /**
     * Store the admin decision for the project report ( fictionnal deletion )
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $admin | id of the admin
     * @param  int $report | id of the report
     * @param  int $project | id of the project
     * @return \Illuminate\Http\Response
     * 
     */
    public function storeAdminDecisionForProjectReport(Request $request, $admin, $report, $project)
    {
        //je récupère le signalement
        $report = Report::findOrFail($report);
        //je récupère le projet
        $project = Project::findOrFail($project);

        //si le bouton de soumission est désapprouvé
        if ($request->submit == 'disapprove') {

            //je passe le report à lu
            $report->update(['read_at' => Carbon::now()]);

            //je redirige l'admin vers la page d'index
            return redirect()->route('admin.indexReports', [
                'adminId' => auth()->user()->id,
            ]);
        }

        // la suppression fictive du projet passe à 1 (il ne sera plus visible depuis l'espace utilisateur et admin)
        $project->fictionnal_deletion =  1;
        // je sauve le projet
        $project->save();

        //si le projet n'est pas modifié
        if (!$project->wasChanged()) {
            //je redirige l'admin à la page du signalement avec un message d'erreur
            return redirect()->route('admin.showProjectReport', [
                'adminId' => auth()->user()->id,
                'report' => $report,
                'project' => $project
            ])->with('status', "Une erreur s'est produite lors de la suppression du projet.");
        }

        //autrement, je passe le signalement à lu
        $report->update(['read_at' => Carbon::now()]);

        //je récupère tous les autres signalements relatifs au projet.
        $anotherReports = $project->reports()->where('id', '!=', $report->id)->get();
        //s'il y a effectivement d'autres sugnalements
        if ($anotherReports->count() > 0) {
            //pour chaqun d'eux
            foreach ($anotherReports as $unreadReport) {
                //je les passe en lu
                $unreadReport->update(['read_at' => Carbon::now()]);
            }
        }

        //je récupère l'auteur du projet
        $projectAuthor = $project->user;

        //je le notifie concernant la suppression de son projet
        $projectAuthor->notify(new SendMailToAuthorConcerningProjectDeletion($project, $projectAuthor));

        //je redirige l'admin vers la page des signalements
        return  redirect()->route('admin.indexReports', [
            'adminId' => auth()->user()->id,
        ])->with('status', 'Le projet a été retiré.');
    }

    /**
     * Store the admin decision for the topic report ( fictionnal deletion )
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $admin | id of the admin
     * @param  int $report | id of the report
     * @param  int $project | id of the topic
     * @return \Illuminate\Http\Response
     * 
     */
    public function storeAdminDecisionForTopicReport(Request $request, $admin, $report, $topic)
    {

        //je récupère le signalement
        $report = Report::findOrFail($report);
        //je récupère le topic
        $topic = Topic::findOrFail($topic);

        //je récupère le project relatif au topic
        $project = $topic->topicable;

        //si l'admin désapprouve le signalement
        if ($request->submit == 'disapprove') {

            //je passe celui en lu
            $report->update(['read_at' => Carbon::now()]);
            //je redirige l'utilisateur vers la page des signalements
            return redirect()->route('admin.indexReports', [
                'adminId' => auth()->user()->id,
            ]);
        }

        //autrement, je passe la suppression fictive du topic à 1
        $topic->fictionnal_deletion =  1;
        //je l'édite
        $topic->save();

        //si ce topic n'est pas édité
        if (!$topic->wasChanged()) {
            //je redirige l'admin vers la page du topic avec un message d'erreur
            return redirect()->route('admin.showTopicReport', [
                'adminId' => auth()->user()->id,
                'report' => $report,
                'topic' => $topic

            ])->with('error', "Une erreur s'est produite lors de la suppression du topic .");
        }

        //autrement je passe le signalement à lu
        $report->update(['read_at' => Carbon::now()]);

        //je récupère les autres éventuels signalements relatifs à ce topic
        $anotherReports = $topic->reports()->where('id', '!=', $topic->id)->get();

        // s'il en existe bien
        if ($anotherReports->count() > 0) {
            //pour chacun d'eux
            foreach ($anotherReports as $unreadReport) {
                //je passe le signalement à lu
                $unreadReport->update(['read_at' => Carbon::now()]);
            }
        }

        //je récupère l'auteur du topic
        $topicAuthor = $topic->user;

        //je le notifie concernant la suppression de son topic
        $topicAuthor->notify(new SendMailToAuthorConcerningTopicDeletion($topic, $project, $topicAuthor));

        //e redirige l'admin vers la page des signalements avec un message de confirmation
        return  redirect()->route('admin.indexReports', [
            'adminId' => auth()->user()->id,
        ])->with('status', 'Le commentaire a été retiré.');
    }

    /**
     * Store the admin decision for the comment report ( fictionnal deletion )
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $admin | id of the admin
     * @param  int $report | id of the report
     * @param  int $project | id of the topic
     * @return \Illuminate\Http\Response
     * 
     */
    public function storeAdminDecisionForCommentReport(Request $request, $admin, $report, $comment)
    {

        //je récupère le signalement
        $report = Report::findOrFail($report);
        //je récupère le commentaire
        $comment = Comment::findOrFail($comment);
        //je récupère le projet relatif au commentaire
        $project = $comment->commentable;
        //si le signalement est désapprouvé
        if ($request->submit == 'disapprove') {
            //l'admin est redirigé vers la page des signalements
            return redirect()->route('admin.indexReports', [
                'adminId' => auth()->user()->id,
            ]);
        }
        //autrement, le commentaire est édité et la suppression fictive passe à 1
        $comment->fictionnal_deletion =  1;
        //je le sauve
        $comment->save();

        //si le commentaire n'est pas édité
        if (!$comment->wasChanged()) {
            //je redirige l'administrateur vers la page du signalement avec un message d'erreur
            return redirect()->route('admin.showCommentReport', [
                'adminId' => auth()->user()->id,
                'report' => $report,
                'comment' => $comment

            ])->with('status', "Une erreur s'est produite lors de la suppression du commentaire.");
        }

        //autrement je passe le signalement à lu
        $report->update(['read_at' => Carbon::now()]);
        //je récupère les autres éventuels signalement relatifs au commentaire
        $anotherReports = $comment->reports()->where('id', '!=', $comment->id)->get();
        //s'il en existe bien
        if ($anotherReports->count() > 0) {
            //pour chacun d'eux
            foreach ($anotherReports as $unreadReport) {
                //je passe le signalement à lu
                $unreadReport->update(['read_at' => Carbon::now()]);
            }
        }
        //je recupère l'auteur du commentaire
        $commentAuthor = $comment->user;
        //je le notifie concernant la suppression de son commentaire
        $comment->user->notify(new SendMailToAuthorConcerningCommentDeletion($comment, $project, $commentAuthor));
        //je redirige l'administrateur vers la page des signalements avec un message de confirmation
        return  redirect()->route('admin.indexReports', [
            'adminId' => auth()->user()->id,
        ])->with('status', 'Le commentaire a été retiré.');
    }
}
