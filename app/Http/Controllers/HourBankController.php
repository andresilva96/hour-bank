<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Schedule;
use App\Models\Task;
use Carbon\Carbon;
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

    public function startTask($id)
    {
        Task::find($id)->schedules()->create(['start' => Carbon::now()]);
        return redirect()->back();
    }

    public function endSchedule($id)
    {
        $schedule = Schedule::find($id);
        $schedule->end = Carbon::now();
        $schedule->save();
        return redirect()->back();
    }

    public function deleteSchedule($id)
    {
        Schedule::find($id)->delete();
        return redirect()->back();
    }
}
