<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\DifficultyLevel;
use App\Models\Project;
use App\Models\Status;
use App\Models\UnityOfMeasurement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $allMembers = User::where('role_id', NULL)->get();

        $user = $allMembers->random();

        $pIdNumber = mt_rand(100000, 999999);


        session(['project_identifier' => $pIdNumber]);

        $pIdentifier = session('project_identifier');

        $catOne = Category::find(1);
        $catTwo = Category::find(2);
        $catThree = Category::find(3);
        $catFour = Category::find(4);
        $catFive = Category::find(5);
        $catSix = Category::find(6);

        $arrOne = Storage::files('public/images_faker/' . $catOne->slug);
        $arrTwo = Storage::files('public/images_faker/' . $catTwo->slug);
        $arrThree = Storage::files('public/images_faker/' . $catThree->slug);
        $arrFour = Storage::files('public/images_faker/' . $catFour->slug);
        $arrFive = Storage::files('public/images_faker/' . $catFive->slug);
        $arrSix = Storage::files('public/images_faker/' . $catSix->slug);

        //je réunnis toutes les images de chaque dossier dans un tableau
        $getAllFilesOfSubFolders = array_merge($arrOne, $arrTwo, $arrThree, $arrFour, $arrFive, $arrSix);
        //je récupère l'image random
        $randomImage = $getAllFilesOfSubFolders[array_rand($getAllFilesOfSubFolders)];
        //je récupère uniquement le nom de l'image
        $imageName = substr(strrchr($randomImage, "/"), 1);

    
        if (str_contains($randomImage, $catTwo->slug)) {
            $projectCatId = $catTwo->id;
        } elseif (str_contains($randomImage, $catThree->slug)) {
            $projectCatId = $catThree->id;
        } elseif (str_contains($randomImage, $catFour->slug)) {
            $projectCatId = $catFour->id;
        } elseif (str_contains($randomImage, $catFive->slug)) {
            $projectCatId = $catFive->id;
        } elseif (str_contains($randomImage, $catSix->slug)) {
            $projectCatId = $catSix->id;
        } else {
            $projectCatId = $catOne->id;
        }

        $storagePath = 'public/projets/' . $user->username . '/projet_' . $pIdentifier . '/thumbnail';

        if (!Storage::exists($storagePath)) {

            Storage::makeDirectory($storagePath);
        }

        Storage::copy($randomImage, $storagePath . '/' . $imageName);


$randomNumber = $this->faker->numberBetween(1,35);

        if($randomNumber > 1){
            $duration = 1;
        }else{
            $duration = 2;
        }

        return [
            'user_id' => $user->id,
            'title' => $this->faker->sentence(),
            // 'category_id' => Category::all()->random()->id,
            'category_id' => $projectCatId,
            'id_number' => $pIdentifier,
            'duration' => $duration,
            // 'thumbnail' => $this->faker->image(public_path('/projets/'. $user->username .'/projets_'.$pIdentifier) ,400,300, null, false) , 
            // 'thumbnail' => Storage::disk('public')->copy('images_faker', $storagePath),
            'thumbnail' => $imageName,
            'difficulty_level_id' => DifficultyLevel::all()->random()->id,
            'unity_of_measurement_id' => UnityOfMeasurement::all()->random()->id,
            'status_id' => Status::all()->random()->id,
            'budget' => $this->faker->numberBetween(1, 99),
            'content' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Fuga qui obcaecati earum exercitationem a amet nobis debitis necessitatibus tenetur, nulla illum voluptate, sit asperiores voluptatem consequuntur mollitia porro sunt ullam atque explicabo ratione illo saepe sint velit. Facere fugiat beatae numquam, ullam cum quo recusandae.'


        ];
    }
}
