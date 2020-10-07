<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\HomeController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TopicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BanController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


/*********** Validation de compte ***********/

/**
 * @description Validate the user account.
 * @param user username of user
 * @param token token of the user
 */
Route::get('utilisateur/validation/{user}/{token}', [ValidationController::class, 'validateUser'])->name('validation.validateUser');

/**
 * @description Validate the user account.
 * @param admin id of admin
 * @param token token of the user
 */
Route::get('admin/validation/{admin}/{token}', [ValidationController::class, 'validateAdmin'])->name('validation.validateAdmin');


/*********** Déconnexion ***********/

/**
 * @description Logout the authenticate user
 */
Route::get('/deconnexion', [LogoutController::class, 'logout'])->name('logout.create')->middleware('member.or.admin');



/* Routes vues par les invités */

Route::middleware(['guest'])->group(function () {

    /*********** Accueil ***********/

    /**
     * @description Show the home page of the site
     */
    Route::get('/', [HomeController::class, 'index'])->name('home.index');

    /************ Connexion ***********/

    /**
     * @description Show the login page
     */
    Route::get('/connexion', [LoginController::class, 'create'])->name('login.create');

    /**
     * @description Authenticate the user
     */
    Route::post('/connexion', [LoginController::class, 'store'])->name('login.store');


    /*********** Inscription ***********/

    /**
     * @description Show the sign up page
     */
    Route::get('/inscription', [RegisterController::class, 'create'])->name('register.create');

    /**
     * @description Create the user in database
     */
    Route::post('/inscription', [RegisterController::class, 'store'])->name('register.store');


    /*********** Mot de passe oublié ***********/

    /**
     * @description Show the forgot password form
     */
    Route::get('/mot-de-passe-oublie', [ForgotPasswordController::class, 'create'])->name('forgotPwd.create');

    /**
     * @description check the user's email in database
     */
    Route::post('/mot-de-passe-oublie', [ForgotPasswordController::class, 'store'])->name('forgotPwd.store');


    /*********** Réinitialisation du mot de passe ***********/

    /**
     * @description Show the reset password form
     * @param user username of user
     * @param token_reset reset token of the user
     */
    Route::get('/reinitialisation-du-mot-de-passe/{user}/{token_reset}', [ResetPasswordController::class, 'edit'])->name('resetPassword.edit');

    /**
     * @description Store new password and notify user about his new password
     * @param user username of user
     * @param token_reset reset token of the user
     */
    Route::patch('/reinitialisation-du-mot-de-passe/{user}/{token_reset}', [ResetPasswordController::class, 'update'])->name('resetPassword.update');
});



/* Routes vues par les invités et les membres */

Route::middleware(['guest.or.member'])->group(function () {


    /*********** Projets ***********/

    /**
     * @description Show the listing of the projects.
     */
    Route::get('projets/', [ProjectController::class, 'index'])->name('projects.index');

    /**
     * @description Show the project
     * @param project id of the project
     * @param slug slug of the project
     */
    Route::get('projets/{project}/{slug}/', [ProjectController::class, 'show'])->name('projects.show');

    /*********** Recherche de projets ***********/

    /**
     * @description Show the projects search
     */
    Route::get('projets/recherche', [SearchController::class, 'search'])->name('projectsSearch.result');

    /*********** Contactez-nous ***********/

    /**
     * @description Show the contact form
     */
    // Route::get('/contactez-nous', [ContactController::class, 'create'])->name('contact.create');

    /**
     * @description Send the sender's message
     */
    // Route::post('/contactez-nous', [ContactController::class, 'store'])->name('contact.store');
});

Route::middleware(['member'])->group(function () {

    /*********** Projets ***********/

    Route::name('projects.')->group(function () {

        /**
         * @description Show the project creation form
         */
        Route::get('/projets/je-poste-un-projet', [ProjectController::class, 'create'])->name('create');

        /**
         * @description Store the new project in database
         */
        Route::post('/projets', [ProjectController::class, 'store'])->name('store');

        /**
         * @description Show the project edition form
         * @param project id of the project
         * @param slug slug of the project
         */
        Route::get('/projets/{project}/{slug}/modifier-mon-projet/{token}', [ProjectController::class, 'edit'])->name('edit');

        /**
         * @description Edit the project in database
         * @param project id of the project
         * @param slug slug of the project
         */
        Route::patch('/projets/{project}/{slug}', [ProjectController::class, 'update'])->name('update');

        /**
         * @description Delete the project in database
         * @param project id of the project
         * @param slug slug of the project
         */
        Route::delete('/projets/{project}/{slug}', [ProjectController::class, 'destroy'])->name('delete');


        /*********** Projets brouillons ***********/

        /**
         * @description Show the drafted project form
         * @param project id of the project
         * @param slug slug of the project
         */
        Route::get('/projets/{project}/{slug}/modifier-mon-brouillon/{token}', [ProjectController::class, 'draft'])->name('draft');


        Route::patch('/projets/{project}/{slug}/modifier-mon-brouillon', [ProjectController::class, 'updateDraft'])->name('updateDraft');
    });


    /*********** Commentaires ***********/

    Route::name('comments.')->group(function () {

        /**
         * @description Store the comment in database
         * @param project id of the project
         * @param slug slug of the project
         */
        Route::post('projets/{project}/{slug}/commentaires/', [CommentController::class, 'store'])->name('store');

        /**
         * @description Edit the comment in database
         * @param project id of the project
         * @param slug slug of the project
         * @param comment id of the comment
         */
        Route::patch('projets/{project}/{slug}/commentaires/{comment}/', [CommentController::class, 'update'])->name('update');

        /**
         * @description Store the comment reply in database
         * @param project id of the project
         * @param slug slug of the project
         * @param comment id of the comment reply
         */
        Route::post('projets/{project}/{slug}/commentaires/{comment}/reponse-au-commentaire/', [CommentController::class, 'storeReply'])->name('storeReply');

        /**
         * @description Edit the comment reply in database
         * @param project id of the project
         * @param slug slug of the project
         * @param comment id of the comment reply
         */
        Route::patch('projets/{project}/{slug}/commentaires/{comment}/reponse-au-commentaire/', [CommentController::class, 'updateReply'])->name('updateReply');
    });

    /*********** Topics ***********/

    Route::name('topics.')->group(function () {
        /**
         * @description Store the topic in database
         * @param project id of the project
         * @param slug slug of the project
         */
        Route::post('projets/{project}/{slug}/topics/', [TopicController::class, 'store'])->name('store');

        /**
         * @description Edit the topic in database
         * @param project id of the project
         * @param slug slug of the project
         * @param topic id of the topic
         */
        Route::patch('projets/{project}/{slug}/topics/{topic}/', [TopicController::class, 'update'])->name('update');

        /**
         * @description Store the topic reply in database
         * @param project id of the project
         * @param slug slug of the project
         * @param topic id of the topic reply
         */
        Route::post('projets/{project}/{slug}/topics/{topic}/reponse-au-topic/', [TopicController::class, 'storeReply'])->name('storeReply');

        /**
         * @description Edit the topic reply in database
         * @param project id of the project
         * @param slug slug of the project
         * @param topic id of the topic reply
         */
        Route::patch('projets/{project}/{slug}/topics/{topic}/reponse-au-topic/', [TopicController::class, 'updateReply'])->name('updateReply');
    });

    /*********** Profil utilisateur ***********/

    Route::name('profiles.')->group(function () {
        /**
         * @description Show the profil edition form.
         * @param user username of user
         * @param token token of the user
         */
        Route::get('/profil-membre/{user}/modifier-mon-compte/{token}', [ProfileController::class, 'edit'])->name('edit');

        /**
         * @description Update the profile in database.
         * @param user username of user
         */
        Route::patch('/profil-membre/{user}/modifier-mon-compte', [ProfileController::class, 'update'])->name('update');

        /**
         * @description Show the listing of published projects on user's profile
         * @param user username of user
         */
        Route::get('/profil-membre/{user}/projets', [ProjectController::class, 'listPublishedProjectsFromProfile'])->name('indexPublishedProjects');

        /**
         * @description Show the listing of drafted projects on user's profile
         * @param user username of user
         */
        Route::get('/profil-membre/{user}/mes-brouillons', [ProjectController::class, 'listDraftedProjectFromProfile'])->name('indexDraftedProjects');

        /**
         * @description Delete the user profile in database
         * @param user username of user
         */
        Route::delete('/suppression-du-profil/{user}', [ProfileController::class, 'destroy'])->name('delete');
    });

    /*********** Signalements ***********/

    Route::name('reports.')->group(function () {


        /**
         * @description Store project report
         * @param project id of the project
         * @param slug slug of the project
         * @param user username of user
         */
        Route::post('signalements/projet-{project}/signalement-fait-par-{user}/', [ReportController::class, 'storeProjectReport'])->name('storeProjectReport');

        /**
         * @description Store topic report
         * @param project id of the project
         * @param slug slug of the project
         * @param user username of user
         */
        Route::post('signalements/question-{topic}/signalement-fait-par-{user}/', [ReportController::class, 'storeTopicReport'])->name('storeTopicReport');

        /**
         * @description Store topic reply report
         * @param project id of the project
         * @param slug slug of the project
         * @param user username of user
         */
        Route::post('signalements/reponse-{topic}/signalement-fait-par-{user}/', [ReportController::class, 'storeTopicReplyReport'])->name('storeTopicReplyReport');

        /**
         * @description Store comment report
         * @param project id of the project
         * @param slug slug of the project
         * @param user username of user
         */
        Route::post('signalements/commentaire-{comment}/signalement-fait-par-{user}/', [ReportController::class, 'storeCommentReport'])->name('storeCommentReport');

        /**
         * @description Store comment reply report
         * @param project id of the project
         * @param slug slug of the project
         * @param user username of user
         */
        Route::post('signalements/commentaires/commentaire-{comment}/signalement-fait-par-{user}/', [ReportController::class, 'storeCommentReplyReport'])->name('storeCommentReplyReport');
    });

    /*********** Notifications ***********/

    /**
     * @description Show the notification concerning comment / comment reply
     * @param project id of the project
     * @param notfiication id of the notification
     */
    Route::get('commentaires/{project}/{notification}', [NotificationController::class, 'showCommentFromNotification'])->name('notifications.showCommentFromNotification');

    /**
     * @description Show the notification concerning topic / topic reply
     * @param project id of the project
     * @param notfiication id of the notification
     */
    Route::get('topics/{project}/{notification}', [NotificationController::class, 'showTopicFromNotification'])->name('notifications.showTopicFromNotification');
});


//-----------------------------------------PARTIE ADMINISTRATEUR-----------------------------------//

Route::middleware(['admin'])->name('admin.')->group(function () {

    /**
     * @description prefix of the following routes
     * @param admin id of the administrator
     */
    Route::prefix('espace-administrateur/{adminId}')->group(function () {

        /*********** Gestion utilisateurs ***********/

        /**
         * @description Show the listing of users
         */
        Route::get('/utilisateurs', [UserController::class, 'index'])->name('indexUsers');

        /**
         * @description Show the user registration form
         */
        Route::get('utilisateurs/creer-un-nouvel-utilisateur', [UserController::class, 'adminCreateUser'])->name('createUser');

        /**
         * @description Store the new user in database
         */
        Route::post('/utilisateurs', [UserController::class, 'adminStoreUser'])->name('storeUser');

        /**
         * @description Show the user edition form from the backend
         * @param user username of the user
         */
        Route::get('/utilisateurs/utilisateur-{user}', [ProfileController::class, 'adminEditUserProfile'])->name('editUser');

        /**
         * @description Update the user in database
         * @param user username of the user
         */
        Route::patch('/utilisateurs/utilisateur-{user}', [ProfileController::class, 'adminUpdateUserProfile'])->name('updateUser');

        /**
         * @description Delete the user from backend
         * @param user username of the user
         */
        Route::delete('/utilisateurs/utilisateur-{user}', [ProfileController::class, 'adminDeleteUserProfile'])->name('deleteUser');

        /**
         * @description Show users from search result
         */
        Route::get('/utilisateurs/recherche', [SearchController::class, 'searchUsers'])->name('search');


        /*********** Ban de l'utilisateur ***********/

        /**
         * @description Show the listing of bans
         */
        Route::get('/bannissements', [BanController::class, 'index'])->name('indexBans');

        /**
         * @description Store ban in database
         * @param user username of the user who's banned
         */
        Route::post('/bannissements/ban/utilisateur-{user}', [BanController::class, 'store'])->name('storeBan');

        /**
         * @description Delete the banned user in database
         * @param ban id of the "banned user"
         */
        Route::delete('/bannissements/ban/{ban}', [BanController::class, 'destroy'])->name('deleteBan');


        /*********** Gestion du compte admin ***********/

        /**
         * @description Show the admin edition form
         */

        Route::get('/mon-compte/{token}', [AdminController::class, 'edit'])->name('edit');

        /**
         * @description Update the admin in database
         */
        Route::patch('/mon-compte/modifier/{token}', [AdminController::class, 'update'])->name('update');

        /**
         * @description Logout the admin
         */
        Route::get('/deconnexion', [AdminController::class, 'destroy'])->name('logout');



        /*********** Projets ***********/

        /**
         * @description Show listing of projects
         */
        Route::get('/projets', [ProjectController::class, 'adminIndexProjects'])->name('indexProjects');

        /**
         * @description Show the project
         * @param project id of the project
         * @param slug slug of the project
         */
        Route::get('/projets/{project}/{slug}', [ProjectController::class, 'adminShowProject'])->name('showProject');

        /**
         * @description Delete project from database
         * @param project id of the project
         */
        Route::delete('projets/{project}', [ProjectController::class, 'adminDeleteProject'])->name('deleteProject');

        /**
         * @description Delete selection projects from database
         */
        Route::delete('/suppression-de-projets-par-selection', [ProjectController::class, 'adminDeleteProjectsSelection'])->name('deleteProjectsSelection');

        /**
         * @description Show project search result
         */
        Route::get('/projets/recherche', [SearchController::class, 'searchProjects'])->name('searchProjects');



        /*********** Topics ***********/

        /**
         * @description Delete the topic in database
         * 
         * @param project id of the project
         * @param slug slug of the project
         * @param topic id of the topic
         */
        Route::delete('projets/{project}/{slug}/topics/topic-{topic}', [TopicController::class, 'adminDeleteTopic'])->name('deleteTopic');



        /*********** Commentaires ***********/

        /**
         * @description Delete the comment in database
         * 
         * @param project id of the project
         * @param slug slug of the project
         * @param comment id of the project
         */
        Route::delete('projets/{project}/{slug}/commentaires/commentaire-{comment}', [CommentController::class, 'adminDeleteComment'])->name('deleteComment');


        /*********** Signalements ***********/

        /**
         * @description Show listing of the reports from backend
         */
        Route::get('/signalements', [ReportController::class, 'index'])->name('indexReports');

        /**
         * @description Show the project report
         * 
         * @param report id of the report
         * @param project id of the project
         */
        Route::get('signalements/signalement-{report}/projet-{project}/', [ReportController::class, 'showProjectReport'])->name('showProjectReport');

        /**
         * @description Delete fictionnaly the project report 
         * 
         * @param report id of the report
         * @param project id of the project
         */
        Route::patch('signalements/decision/signalement-{report}/projet-{project}/', [ReportController::class, 'storeAdminDecisionForProjectReport'])->name('storeAdminDecisionForProjectReport');

        /**
         * @description Show the comment report
         * 
         * @param report id of the report
         * @param comment id of the comment
         */
        Route::get('commentaires/signalements/signalement-{report}/commentaire-{comment}/', [ReportController::class, 'showCommentReport'])->name('showCommentReport');

        /**
         * @description Delete fictionnaly the comment report 
         * 
         * @param report id of the report
         * @param comment id of the comment
         */
        Route::patch('commentaires/signalements/decision/signalement-{report}/commentaire-{comment}/', [ReportController::class, 'storeAdminDecisionForCommentReport'])->name('storeAdminDecisionForCommentReport');

        /**
         * @description Show the topic report
         * 
         * @param report id of the report
         * @param topic id of the topic
         */
        Route::get('topics/signalements/signalement-{report}/topic-{topic}/', [ReportController::class, 'showTopicReport'])->name('showTopicReport');

        /**
         * @description Delete fictionnaly the topic report 
         * 
         * @param report id of the report
         * @param topic id of the topic
         */
        Route::patch('topics/signalements/decision/signalement-{report}/topic-{topic}/', [ReportController::class, 'storeAdminDecisionForTopicReport'])->name('storeAdminDecisionForTopicReport');


        /*********** Catégories ***********/

        /**
         * @description Show listing of the categories from backend
         */
        Route::get('/categories', [CategoryController::class, 'index'])->name('indexCategories');

        /**
         * @description Store category in database
         */
        Route::post('/categories', [CategoryController::class, 'store'])->name('storeCategory');

        /**
         * @description Update category in database
         * @param category slug of the category
         */
        Route::patch('/categories/categorie-{category}', [CategoryController::class, 'update'])->name('updateCategory');

        /**
         * @description Delete category in database
         * @param category slug of the category
         */
        Route::delete('/categories/categorie-{category}', [CategoryController::class, 'delete'])->name('deleteCategory');


        /*********** Administrateurs ***********/

        /**
         * @description Show listing of the admins from super admin account
         */
        Route::get('/administrateurs', [AdminController::class, 'indexAdmins'])->name('indexAdmins');

        /**
         * @description Show the admin creation form
         */
        Route::get('/administrateurs/creer-un-administrateur', [AdminController::class, 'createAdmin'])->name('createAdmin');

        /**
         * @description Store admin in database
         */
        Route::post('/administrateurs', [AdminController::class, 'storeAdmin'])->name('storeAdmin');

        /**
         * @description Show the admin profile edition form
         * 
         * @param adminUser id of the admin
         */
        Route::get('/administrateurs/administrateur-{adminUser}', [AdminController::class, 'editAdmin'])->name('editAdmin');

        /**
         * @description Update the admin in database
         * 
         * @param adminUser id of the admin
         */
        Route::patch('/administrateurs/administrateur-{adminUser}/modifier-l-administrateur', [AdminController::class, 'updateAdmin'])->name('updateAdmin');

        /**
         * @description Delete the admin from database
         * 
         * @param adminUser id of the admin
         */
        Route::delete('/administrateurs/administrateur-{adminUser}', [AdminController::class, 'destroyAdmin'])->name('deleteAdmin');

        /**
         * @description Show listing of admins from search result
         * 
         */
        Route::get('/administrateurs/recherche', [SearchController::class, 'searchAdmins'])->name('searchAdmins');
    });
});
