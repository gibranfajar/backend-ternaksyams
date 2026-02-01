<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FlashSale;
use App\Models\VariantSize;
use Illuminate\Support\Facades\DB;

class FlashSaleScheduler extends Command
{
    protected $signature = 'flashsale:sync';
    protected $description = 'Auto activate & finish flash sale + restore stock';

    public function handle()
    {
        DB::transaction(function () {

            /** =======================
             *  ACTIVATE FLASH SALE
             *  ======================= */
            FlashSale::where('status', 'draft')
                ->where('start_date', '<=', now())
                ->where('end_date', '>', now())
                ->update(['status' => 'ongoing']);

            /** =======================
             *  FINISH FLASH SALE
             *  ======================= */
            $endedFlashSales = FlashSale::where('status', 'ongoing')
                ->where('end_date', '<=', now())
                ->get();

            foreach ($endedFlashSales as $flashSale) {

                foreach ($flashSale->items as $item) {
                    // restore remaining stock
                    VariantSize::where('id', $item->variantsize_id)
                        ->increment('stock', $item->stock);
                }

                // mark as finished
                $flashSale->update(['status' => 'finished']);

                // soft delete
                $flashSale->delete();
            }
        });

        $this->info('Flash sale sync completed');
    }
}
