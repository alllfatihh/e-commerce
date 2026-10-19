<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\ProductStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

#[Signature('orders:cancel-expired {--hours=24 : Jam kedaluwarsa pesanan pending (default 24 jam)}')]
#[Description('Batalkan pesanan pending yang sudah kedaluwarsa dan kembalikan stok produk secara otomatis')]
class CancelExpiredOrders extends Command
{
    public function handle()
    {
        $hours = (int) $this->option('hours');
        if ($hours <= 0) $hours = 24;

        $threshold = now()->subHours($hours);
        $this->info("Mencari pesanan pending yang dibuat sebelum {$threshold->toDateTimeString()} ({$hours} jam lalu)...");

        $expiredOrders = Order::with('items')
            ->where('status', 'pending')
            ->where('created_at', '<=', $threshold)
            ->get();

        if ($expiredOrders->isEmpty()) {
            $this->info("Tidak ada pesanan kedaluwarsa yang ditemukan.");
            return;
        }

        $cancelledCount = 0;
        $totalItemsRestored = 0;

        foreach ($expiredOrders as $order) {
            DB::transaction(function () use ($order, &$totalItemsRestored) {
                $order->update(['status' => 'cancelled']);

                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        ProductStock::where('product_id', $item->product_id)
                            ->where('size', $item->size)
                            ->increment('stock', $item->quantity);

                        $totalItemsRestored += $item->quantity;
                    }
                }
            });

            // Coba batalkan transaksi di Midtrans jika ada midtrans_order_id
            try {
                \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
                $midtransId = $order->midtrans_order_id ?? ($order->id . '-' . strtotime($order->created_at));
                \Midtrans\Transaction::cancel($midtransId);
            } catch (\Exception $e) {
                // Abaikan jika tidak ada di Midtrans
            }

            Log::info("Pesanan #NTS-{$order->id} otomatis dibatalkan karena kedaluwarsa (> {$hours} jam). Stok dikembalikan.");
            $this->line("Pesanan #NTS-{$order->id} berhasil dibatalkan. Stok dikembalikan.");
            $cancelledCount++;
        }

        $this->info("Selesai! {$cancelledCount} pesanan kedaluwarsa dibatalkan, {$totalItemsRestored} unit stok dikembalikan ke etalase.");
    }
}
