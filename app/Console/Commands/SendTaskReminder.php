<?php

namespace App\Console\Commands;

use App\Models\LeadNotification;
use App\Models\LeadTaskDetail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendTaskReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-task-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * Execute the console command.
     */
    public function handle()
{
    $now = Carbon::now();

    $followUpTasks = LeadTaskDetail::with('leadTask')
        ->where('status', '!=', 1)
        ->where(function ($query) use ($now) {
            $query->whereNotNull('reminderDate')
                ->where('reminderDate', '>=', $now->toDateTimeString());
        })->orWhere(function ($query) use ($now) {
            $query->whereNull('reminderDate')
                ->whereDate('dead_line', '>', $now->toDateString());
        })
        ->orderBy('id', 'desc')
        ->get();

    if ($followUpTasks->count() > 0) {
        foreach ($followUpTasks as $task) {
            $reminderDate = $task->reminderDate;
            $deadlineDate = $task->dead_line;
            
            $leadId = $task->leadTask->lead_id ?? null;
            $taskTitle = $task->leadTask->task_title;
            Log::info("Task Title: $leadId , $taskTitle ");
            if ($reminderDate && strtotime($reminderDate) > time()) {
                $title = 'Reminder Alert';
                $description = "Heads up! '" . $taskTitle . "' is coming up—don’t forget to update it.";
            } else {
                $title = 'Deadline Alert';
                $description = "Deadline: '" . $taskTitle . "' is due soon. Please take action.";
            }


            if ($leadId) {
                LeadNotification::create([
                    'user_id'     => $task->task_id,
                    'lead_id'     => $leadId,
                    'title'       => $title,
                    'description' => $description,
                    'task_id'     => $task->task_id,
                    'status'      => 0,
                ]);
            }
        }

        $this->info('Notifications sent successfully.');
        return 0;  // Return a success exit code (0 means success in console commands)
    }

    $this->info('No tasks found to send notifications.');
    return 1;  // Return a failure exit code (1 means failure in console commands)
}



}
