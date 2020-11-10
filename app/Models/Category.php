<?php

namespace App\Models;

use App\Jobs\SendMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Notification;

use App\Notifications\SendMailToUserReferingToCreatingCategory;
use Carbon\Carbon;

class Category extends Model
{

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($category) {

            $category->slug = Str::slug($category->name);
        });
    }


    // une catégorie peut faire référence à plusieurs projets.
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
