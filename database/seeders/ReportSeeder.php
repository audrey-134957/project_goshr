<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Motive;
use App\Models\Project;
use App\Models\Report;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $contentsIds = array_merge(
            Project::all()->modelKeys(),
            Comment::all()->modelKeys(),
            Topic::all()->modelKeys()
        );

        // dd($contentsIds);

        // $getSmallestContentId = min($contentsIds);
        // $getHigherContentId = max($contentsIds);

        // dd($getHigherContentId);

        for ($i = 1; $i <= 15; $i++) {

            $user = User::all()->random();


            Report::create(
                [
                    'user_id' => $user->id,
                    'reportable_id' => Project::where('user_id', '!=', $user->id)->get()->random()->id,
                    'reportable_type' => Project::class
                ]
            );

            Report::create(
                [
                    'user_id' => $user->id,
                    'reportable_id' => Comment::where('user_id', '!=', $user->id)->get()->random()->id,
                    'reportable_type' => Comment::class
                ]
            );

            Report::create(
                [
                    'user_id' => $user->id,
                    'reportable_id' => Topic::where('user_id', '!=', $user->id)->get()->random()->id,
                    'reportable_type' => Topic::class
                ]
            );

            $report = Report::all()->random();
            $motive = Motive::all()->random();

            $report->motives()->save($motive);
        }
    }
}
