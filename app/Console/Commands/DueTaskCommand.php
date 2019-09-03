<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Task;
use App\Project;
use App\User;
use App\TaskHasMember;

class DueTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dueDate:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends an email to every user about the task which is due tommorow';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*
        $startTime = Carbon::now('+12');
        $endTime = Carbon::now('+24');
        $dueTasks = Task::whereBetween('due_date',array($startTime,$endTime));
        return $dueTasks;
        */
        //SendEmails::dispatch($user,"dueTaskToNotify",$task_details);
        echo("hello");
    }
}
