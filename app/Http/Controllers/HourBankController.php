<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class HourBankController extends Controller
{
    public function index(Request $request, $hash)
    {
        if ($data = $request->all()) {
            foreach ($data['tasks'] as $task) {
                Task::create(['name' => $task, 'value' => $data['value']]);
            }
            return redirect()->back();
        }
        $project = Project::where('hash', $hash)->first();
        return view('hour-bank', ['project' => $project]);
    }
}
