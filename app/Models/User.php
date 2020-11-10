<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as BasicAuthenticatable;
// use Illuminate\Contracts\Auth\Authenticatable as AuthAuthenticatable;
use Illuminate\Support\Facades\Storage;
use App\Notifications\SendEmailToUserReferingToDeletingProfile;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class User extends Model implements Authenticatable
{
    use Notifiable;
    use BasicAuthenticatable;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'firstname',
        'email',
        'username',
        'avatar',
        'password',
        'email_verified_at',
        'token',
        'token_reset',
        'token_account',
        'rank_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $with = [
        'level',
        'profile'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // pour générer automatiquement le token
    protected static function boot()
    {
        parent::boot();

        //lors de la création de l'utilisateur je vais lui assigner un token
        static::creating(function ($user) {

            $token = bcrypt(Str::random(60));
            $identifier = mt_rand(000000, 999999);
            //comme bcrypt chiffre le "Str::random", il va rajouter des caractères spéciaux à la route. On aura un problème de route.
            //Il faut donc fait appel aux str_replace pour remplacer les '/' par des '$' dans le $token
            $user->token = str_replace('/', '$', $token);
            $user->ip = request()->ip();

            $user->user_identifier = $identifier;
        });

        static::created(function ($user){
            if($user->role_id === NULL){
                
                $profile = new Profile();
                $profile->user_id = $user->id;
                $profile->save();
            }
        });

        //chose à faire automatiquement après la suppression de l'utilisateur
        static::deleted(function ($user) {

            //je stoke le pseudonyme de l'utilisateur dans une variable
            $userFolder = $user->username;
            // je crée le chemin du dossier de l'utilisateur que je vais stocker dans la variable
            $storagePath = 'public/avatars/' . $userFolder;
            $userDirectory = 'public/projets/' . $userFolder;

            //si le dossier existe
            if (Storage::exists($storagePath)) {
                //je le supprime
                Storage::deleteDirectory($storagePath);
            }

            if (Storage::exists($userDirectory)) {
                Storage::deleteDirectory($userDirectory);
            }
            //je notifie l'utilisateur par email, lui confirmant que son compte est bien supprimé
            // $user->notify(new SendEmailToUserReferingToDeletingProfile($user));
        });
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRouteKeyName()
    {
        //on veut recupérer l'utilisateur par son pseudonyme
        return 'username';
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function level()
    {
        return $this->belongsTo(Rank::class, 'rank_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function bank_of_token()
    {
        return $this->hasOne(BankOfToken::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function getImage(User $user)
    {
        //je cherche l'utilisateur
        $user = User::findOrFail($user->id);
        //si un utilisateur n'a pas son avatar de téléchargé, dans ce cas, on lui affiche une image pas défault
        $imagePath = $this->avatar ?? 'avatars/default-avatar.png';
        // je retourne l'avatar de l'utilisateur
        return  "/storage/" . $imagePath;
    }


    public function getUserCompleteName(){
        $completeName = $this->firstname.' '.$this->name;

        return $completeName;
    }

    public function getUserCreationDate()
    {
        //je récupère la date de création de l'utilisateur que je transforme en locale FR
        $userCreationDate = Carbon::parse($this->created_at)->locale('fr');
        //je vais ensuite récupérer la date et la transformer en 1 janvier 1010(D M Y) que je vais stocker dans une variable.
        $transformUserCreationDate = $userCreationDate->isoFormat('LL');

        return $transformUserCreationDate;;
    }
}
