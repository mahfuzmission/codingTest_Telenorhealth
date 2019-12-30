<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FlushCookie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flush:cookie';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command flush out existing cookie';

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
        // flash existing cookie
        cookie()->forget('keys');

        $this->info("Cookie flashed out!");
    }
}
