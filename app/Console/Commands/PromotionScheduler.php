<?php

namespace App\Console\Commands;

use App\Models\Promotion;
use Illuminate\Console\Command;

class PromotionScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:promotion-scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle promotion time schedule';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        // 1. Aktifkan promo yang sedang dalam periode
        $activated = Promotion::where('status', 'inactive')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>', $now)
            ->update(['status' => 'active']);

        // 2. Nonaktifkan promo yang sudah lewat end_date
        $deactivated = Promotion::where('status', 'active')
            ->where('end_date', '<=', $now)
            ->update(['status' => 'inactive']);

        $this->info("Activated: {$activated}, Deactivated: {$deactivated}");

        return Command::SUCCESS;
    }
}
