<?php

namespace App\Console\Commands;

use App\Models\Voucher;
use Illuminate\Console\Command;

class VoucherScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:voucher-scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Handle time based vouchers';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();

        // 1. Aktifkan voucher yang waktunya sudah mulai
        $activated = Voucher::where('status', 'draft')
            ->where('start_date', '<=', $now)
            ->where('end_date', '>', $now)
            ->update(['status' => 'active']);

        // 2. Nonaktifkan voucher yang sudah lewat masa berlaku
        $deactivated = Voucher::where('status', 'active')
            ->where('end_date', '<=', $now)
            ->update(['status' => 'inactive']);

        $this->info("Activated: {$activated}, Deactivated: {$deactivated}");

        return Command::SUCCESS;
    }
}
