<?php

namespace Database\Seeders;

use App\Models\Motive;
use Illuminate\Database\Seeder;

class MotiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Motive::create(['name' => 'Contenu dangereux']);
        Motive::create(['name' => 'Contenu choquant/violent']);
        Motive::create(['name' => 'Incitation à la haine/ au harcèlement / au cyberharcèlement']);
        Motive::create(['name' => 'Contenu à but commercial']);
        Motive::create(['name' => 'Spam']);
        Motive::create(['name' => 'Contenu à caractère pornographique']);
        Motive::create(['name' => "Droit d'auteur"]);
    }
}
