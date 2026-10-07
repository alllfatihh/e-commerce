<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Product;

#[Signature('biteship:sync-products')]
#[Description('Sync local products to Biteship Dashboard (Fulfillment)')]
class SyncBiteshipProducts extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai sinkronisasi produk ke Biteship...');

        $products = Product::with('stocks')->get();
        $apiKey = env('BITESHIP_API_KEY');

        if (!$apiKey) {
            $this->error('BITESHIP_API_KEY tidak ditemukan di .env');
            return;
        }

        foreach ($products as $product) {
            if ($product->stocks->count() > 0) {
                foreach ($product->stocks as $stock) {
                    $productName = $product->name . ' - Size ' . $stock->size;
                    $this->syncToBiteship($apiKey, $productName, $product->description ?? 'Notisse Apparel');
                }
            } else {
                $this->syncToBiteship($apiKey, $product->name, $product->description ?? 'Notisse Apparel');
            }
        }

        $this->info('Sinkronisasi selesai!');
    }

    private function syncToBiteship($apiKey, $name, $description)
    {
        $response = Http::withHeaders([
            'Authorization' => $apiKey
        ])->post('https://api.biteship.com/v1/products', [
                    'name' => $name,
                    'description' => $description,
                    'category' => 'fashion',
                ]);

        if ($response->successful()) {
            $this->info('Berhasil upload: ' . $name);
        } else {
            $this->error('Gagal upload: ' . $name . ' (' . $response->body() . ')');
        }
    }
}
