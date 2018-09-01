<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use App\Models\Tatuco\Param;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
          Commands\TatucoBackup::class,
          Commands\TatucoCreateDataBase::class,
          Commands\TatucoInstall::class,
          Commands\Test::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
      

      
        /* $schedule->execute('/usr/bin/pg_dump --host {$host} --port {$port} --username "{$user}" --no-password  --format custom --blobs --verbose --file "/home/maria/laravel-tatuco/laravel" "laravel-tatuco"')*/
        // ->daily()
         //->sendOutputTo('directorio');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
