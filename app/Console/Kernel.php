<?php

namespace App\Console;

use App\Models\Product;
use App\Models\UserProduct;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $userProduct = UserProduct::where("is_rent", 1)->orderBy("expired_at")->first();
            if ($userProduct->is_rent && $userProduct->expired_at <= date("Y-m-d H:i:s")) {
                $product = Product::find($userProduct->product_id);
                $product->in_use--;
                $product->count++;
                try {
                    DB::beginTransaction();
                        $userProduct->delete();
                        $product->save();
                    DB::commit();
                } catch (\PDOException $e) {
                    $this->reportException($e);
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
