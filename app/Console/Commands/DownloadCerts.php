<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DownloadCerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cloudflare:dl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Downloads the current certificates';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dlurl = env('JWT_PUBLIC_KEY_LOCATION');
        $keyfile = file_get_contents($dlurl);
        Storage::disk('local')->put('jwtpub.pem', $keyfile);
        return 0;
    }
}
