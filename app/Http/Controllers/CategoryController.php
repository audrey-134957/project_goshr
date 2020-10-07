<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;

use App\Http\Requests\EditCategory;
use App\Http\Requests\StoreCategory;
use App\Notifications\SendMailToUserReferingToCreatingCategory;
use App\Notifications\SendMailToUserReferingToDeletingCategory;
use App\Notifications\SendMailToUserReferingToUpdatingCategory;
use Illuminate\Support\Str;


class CategoryController extends Controller
{


    /*********** Super Admin ***********/


    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // je récupères les catégories
        $categories = Category::all();
        //j'autorise l'admin à voir les categories
        $this->authorize('viewAny', Category::class);

        return view('admins.categories.index', ['categories' => $categories]);
    }

    /**
     * Store a newly created category in database.
     *
     * @param  \Illuminate\Http\Requests\StoreCategory $request
     * @param string $admin | username of the admin.
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategory $request, $admin)
    {
        //j'autorise l'admin a créer une catgorie.
        $this->authorize('create', Category::class);

        /* Create new catgory */
        $category = new Category();
        $category->name = ucfirst($request->category_name);
        $category->save();

        //si la catégorie n'est pas sauvé
        if (!$category->save()) {
            //si la catégorie n'est pas sauvé, l'amdinistrateur est redirigé avec une erreur
            return redirect()->route('admin.indexCategory', [
                'adminId' => auth()->user()->id
            ])->with('error', "Une erreur s'est produite lors de la création de la catégorie");
        }

        //je récupère tous les utilisateurs membres
        $users = User::where('role_id', NULL)->get();

        //je notifie chaque utilisateur  qu'une nouvelle catégorie est créé.
        foreach ($users as $user) {
            $user->notify(new SendMailToUserReferingToCreatingCategory($category, $user));
        }

        //je redirige l'administrateur avec un status de confirmation
        return redirect()->route('admin.indexCategories', [
            'adminId' => auth()->user()->id
        ])->with('status', 'La catégorie a bien été créée');
    }

    /**
     * Update the category in database.
     *
     * @param  \Illuminate\Http\Requests\EditCategory  $request
     * @param string $admin | username of the admin
     * @param string $category | slug of the category
     * @return \Illuminate\Http\Response
     */
    public function update(EditCategory $request, $admin, $category)
    {
        //je récupère la catégorie
        $category = Category::where('slug', $category)->firstOrFail();
        //j'autorise l'admin à modifié la catégorie.
        $this->authorize('update', $category);
        //je stoke le nom actuel de la catégorie avant son édition
        session(['oldCategoryName' => $category->name]);

        $category->name = ucfirst($request->edit_category_name);
        $category->slug = Str::slug($request->edit_category_name);
        $category->save();

        //si la catégorie n'a pas été modifié
        if (!$category->wasChanged()) {
            // je redirige l'administraeur avec un message d'erreur
            return redirect()->route('admin.indexCategories', [
                'adminId' => auth()->user()->id
            ])->with('error', "Une erreur s'est produite lors de la modification de la catégorie.");
        }

        // je recupère tous mes utilisateurs
        $users = User::where('role_id', NULL)->get();

        foreach ($users as $user) {
            // je notifie chaque utilisateur
            $user->notify(new SendMailToUserReferingToUpdatingCategory($category, $user));
        }
        // je redirige l'administraeur avec un status de confirmation
        return redirect()->route('admin.indexCategories', [
            'adminId' => auth()->user()->id
        ])->with('status', 'La catégorie a bien été modifiée.');
    }

    /**
     * Remove the cateogory from storage.
     * 
     * @param  string  $admin | username of the admin.
     * @param  string  $category | slug of the category
     * @return \Illuminate\Http\Response
     */
    public function delete($admin, $category)
    {
        //je récupère la catégorie
        $category = Category::where('slug', $category)->firstOrFail();

        //j'autorise l'admin à supprimer la catégorie.
        $this->authorize('delete', $category);


        //si la catégirie n'existe pas
        if (!$category) {
            //je redirige l'administrateur vers page des catégories avec une erreur
            return redirect()->route('admin.indexCategories', [
                'adminId' => auth()->user()->id
            ])->with('error', "Une erreur s'est produite lors de la suppression de la catégorie.");
        }

        // si cette catégory contenait des projets
        if ($category->projects()->count() > 0) {
            //pour chaque projet

            $projects = $category->projects()->get();
            foreach ($projects as $project) {

                // dd($category->projects()->get());
                //je vais modifié le status pour qu'il soit en brouillon
                $project->status_id = 1;
                $project->category_id = NULL;
                $project->save();
            }
        }

        //je vais récupérer tous mes utilisateurs
        $users = User::where('role_id', NULL)->get();

        //pour chaque utilisateur
        foreach ($users as $user) {
            //je notifie que la catégorie a été supprimé
            $user->notify(new SendMailToUserReferingToDeletingCategory($category, $user));
        }

        //je supprime la catégorie
        $category->delete();

        //je redirige l'administrateur vers page des catégories avec un status de confirmation
        return redirect()->route('admin.indexCategories', [
            'adminId' => auth()->user()->id
        ])->with('status', 'La catégorie a bien été supprimée.');
    }
}
