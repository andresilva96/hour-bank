<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class HourBankController extends Controller
{
    public function index(Request $request, $hash)
    {
        $project = Project::where('hash', $hash)->first();

        if ($data = $request->all()) {
            foreach ($data['tasks'] as $task) {
                $task = new Task(['content' => $task, 'value' => $data['value']]);
                $project->tasks()->save($task);
            }
            return redirect()->back();
        }
        return view('hour-bank', ['project' => $project]);
    }
}
