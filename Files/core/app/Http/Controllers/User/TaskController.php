<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\TaskResource;
use App\Models\Project;
use App\Models\TrialTask;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{

    public function index()
    {
        abort_if(!gs('trial_task'), 404);

        $pageTitle = "Trial Task List";
        $user = auth()->user();
        $tasks = TrialTask::searchable(['title'])->filter(['status'])->where('user_id', $user->id)->with(['job', 'buyer'])->dateFilter()->orderByDesc('id')->paginate(getPaginate());

        return Inertia::render('User/Task/Index', [
            'pageTitle' => $pageTitle,
            'tasks' => TaskResource::providerTasks($tasks),
        ]);
    }

    public function trialTaskAccept($id)
    {
        abort_if(!gs('trial_task'), 404);
        $user = auth()->user();
        $task = TrialTask::findOrFail($id);
        $task->status = Status::TASK_ACCEPTED;
        $task->save();

        notify($task->buyer, 'TRIAL_TASK_ACCEPTED', [
            'freelancer'  => $user->username,
            'title'  => $task->title,
            'amount' => showAmount($task->amount, currencyFormat: false),
        ]);

        $notify = ['Task accepted successfully'];
        return back()->withNotify($notify);
    }

    public function trialTaskForm($id)
    {
        $pageTitle  = 'Upload Assigned Task';
        $freelancer = auth()->user();
        $mainQuery  = TrialTask::uploadable();
        $task    = (clone $mainQuery)->where('user_id', $freelancer->id)->findOrFail($id);

        //Buyer task assignments
        $projectQuery  = Project::query();
        $buyer                  = $task->buyer;
        $buyerProjectAssignment = (clone $projectQuery)->where('buyer_id', $buyer->id);
        $buyerJobs              = $buyerProjectAssignment->count();
        $buyerSuccessJobs       = (clone $buyerProjectAssignment)->where('status', Status::PROJECT_COMPLETED)->count();
        $buyerSuccessJobPercent = ($buyerJobs > 0) ? ($buyerSuccessJobs / $buyerJobs) * 100 : 0;

        return Inertia::render('User/Task/Upload', [
            'pageTitle' => $pageTitle,
            'task' => TaskResource::providerUpload($task, $buyer, [
                'totalJobs' => $buyerJobs,
                'successJobs' => $buyerSuccessJobs,
                'successPercent' => showAmount($buyerSuccessJobPercent, currencyFormat: false),
            ]),
        ]);
    }


    public function taskUpload(Request $request, $id)
    {
        $freelancer = auth()->user();
        $task = TrialTask::uploadable()->where('user_id', $freelancer->id)->find($id);

        if (!$task) {
            $notify[] = ['error', 'The requested task was not found or has already been completed.'];
            return back()->withNotify($notify);
        }

        if ($freelancer->id !== $task->user_id) {
            $notify[] = ['error', 'You are not authorized to upload files for this task, right now.'];
            return back()->withNotify($notify);
        }

        $allowedExtension = ['zip', 'rar', 'pdf', 'doc', 'docx', 'xls', 'xlsx', '7zip'];
        $request->validate([
            'comments'  => 'nullable|string',
            'task_file' => [
                'required',
                'file',
                function ($attribute, $value, $fail) use ($request, $allowedExtension) {
                    $taskFile = $request->file('task_file');
                    if ($taskFile) {
                        $ext = strtolower($taskFile->getClientOriginalExtension());
                        if (!in_array($ext, $allowedExtension)) {
                            $fail("Only " . implode(', ', $allowedExtension) . " files are allowed.");
                        }
                    } else {
                        $fail("The file is invalid or missing.");
                    }
                },
            ],
        ]);

        if ($request->file('task_file')) {
            try {
                $old                   = basename($task->task_file) ?? '';
                $formTaskFile          = $request->file('task_file');
                $directory             = date("Y") . "/" . date("m") . "/" . date("d");
                $uploadPath            = getFilePath('taskFile') . '/' . $directory;
                $file                  = $directory . '/' . fileUploader($formTaskFile, $uploadPath, null, $old);
                $task->task_file       = $file;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'File could not upload'];
                return $notify;
            }
        }

        $task->comments    = @$request->comments;
        $task->status      = Status::TASK_COMPLETED;
        $task->uploaded_at = now();
        $task->upload_count += 1;
        $task->save();

        notify($task->buyer, 'TRIAL_TASK_SUBMITTED', [
            'freelancer' => $freelancer->fullname,
            'title'      => $task->title,
            'comments'   => $task->comments,
        ]);

        $notify[] = ['success', 'Task file uploaded successfully for buyer review.'];
        return to_route('user.trial.task.index')->withNotify($notify);
    }
    
}
