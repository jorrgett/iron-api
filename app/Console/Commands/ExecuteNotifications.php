<?php

namespace App\Console\Commands;

use App\Helpers\FirebaseMessaging;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExecuteNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Executes the notifications function in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            DB::statement('SELECT push_service_notifications();');

            $firebaseMessaging = new FirebaseMessaging();
            $firebaseMessaging->pushNotifications();
            
            $this->info('Push notifications processed successfully.');
        } catch (\Exception $e) {
            Log::error('Error processing notifications: ' . $e->getMessage());
            $this->error('Error processing notifications: ' . $e->getMessage());
        }

        return 0;
    }
}
