<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use App\Models\mahasiswaService;
use App\Notifications\nilaiNotification;

class SubscribeNilai extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'subscribe:nilai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Subscribe ke Redis channel nilai_ditambahkan';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        Redis::subscribe(['nilai_ditambahkan', 'nilai_update'], function ($message, $channel) {
            $data = json_decode($message, true);

            sleep(10);
            Log::info("Pesan dari channel {$channel}", $data);

            $mahasiswa = mahasiswaService::where('nim', $data['nim'])->first();
            if ($mahasiswa) {
                $mahasiswa->notify(new nilaiNotification($data));
            }
        });
    }
}
