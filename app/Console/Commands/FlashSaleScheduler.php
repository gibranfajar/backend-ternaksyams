<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FlashSale;
use App\Models\VariantSize;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FlashSaleScheduler extends Command
{
    protected $signature = 'flashsale:sync';
    protected $description = 'Auto activate & finish flash sale + restore stock';

    public function handle()
    {
        DB::transaction(function () {

            $activated = FlashSale::where('status', 'draft')
                ->where('start_date', '<=', now())
                ->where('end_date', '>', now())
                ->update(['status' => 'ongoing']);

            Log::info('Activated flashsale', ['count' => $activated]);

            $endedFlashSales = FlashSale::where('status', 'ongoing')
                ->where('end_date', '<=', now())
                ->get();

            foreach ($endedFlashSales as $flashSale) {

                foreach ($flashSale->items as $item) {
                    VariantSize::where('id', $item->variantsize_id)
                        ->increment('stock', $item->stock);
                }

                $flashSale->update(['status' => 'done']);
                $flashSale->delete();
            }
        });

        $this->info('Flash sale sync completed');
    }
}
