<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class deleteOTP extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deleteOTP:min';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Artisan Command to delete OTPs after time';

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
     * @return int
     */
    public function handle()
    {
        echo("asas");
        DB::table('forgot_passwords')->whereRaw('TIMESTAMPDIFF(MINUTE,created_at,NOW()) > 1')->delete();
    }
}