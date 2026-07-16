<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Lib\AdminResource;
use App\Models\TrialTask;
use Inertia\Inertia;

class ManageTaskController extends Controller
{
    public function index()
    {
        if(!gs('trial_task')){
            $notify[] = ['error', 'Trial task feature is currently disabled. Enable it from System Configuration'];
            return to_route('admin.setting.system.configuration')->withNotify($notify);
        }

        $pageTitle = "Trial Task List";
        $tasks = TrialTask::searchable(['title'])->filter(['status'])->with(['job', 'buyer', 'user'])->dateFilter()->orderByDesc('id')->paginate(getPaginate());

        return Inertia::render('Admin/Tasks/Index', [
            'pageTitle' => $pageTitle,
            'tasks' => AdminResource::trialTasks($tasks),
        ]);
    }
    
    public function details($id)
    {
        if(!gs('trial_task')){
            $notify[] = ['error', 'Trial task feature is currently disabled.'];
            return to_route('admin.setting.system.configuration')->withNotify($notify);
        }

        $pageTitle = "Trial Task List";
        $task = TrialTask::with(['job', 'buyer', 'user'])->findOrFail($id);
        return Inertia::render('Admin/Tasks/Detail', [
            'pageTitle' => $pageTitle,
            'task' => AdminResource::trialTaskDetail($task),
        ]);
    }

    public function download($id, $file)
    {
        $task = TrialTask::where('id', $id)->first();

        if (!$task) {
            $notify[] = ['error', 'Task not found!'];
            return back()->withNotify($notify);
        }
        $path = getFilePath('taskFile');
        $file = decrypt($file);
        $fullPath = $path . '/' . $file;
        if (!file_exists($fullPath)) {
            abort(404, 'File not found');
        }
        $title = slug(substr($task->title, 0, 20));
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($fullPath);
        header('Content-Disposition: attachment; filename="' . $title . '.' . $ext . '";');
        header("Content-Type: " . $mimetype);
        return readfile($fullPath);
    }

}
