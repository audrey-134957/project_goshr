<?php

namespace App\View\Components;

use App\Models\Project;
use Illuminate\View\Component;

class Projects extends Component
{


    public $projects;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(Project $projects)
    {
        $this->projects = Project::with('category', 'user', 'materials', 'difficulty_level', 'unity_of_measurement','status')
        ->where('status_id', 2)
        ->orderBy('created_at', 'desc')
        ->get();

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.projects');
    }
}
