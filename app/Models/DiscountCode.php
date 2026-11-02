<?php

namespace App\Models;

use Database\Factories\DiscountCodeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    /** @use HasFactory<DiscountCodeFactory> */
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'value' => 'float',
            'min_order_amount' => 'float',
            'max_discount_amount' => 'float',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'discount_code_product');
    }

    public function isValidForSubtotal(float $subtotal): bool
    {
        return $this->getInvalidReason($subtotal) === null;
    }

    public function getInvalidReason(float $subtotal): ?string
    {
        if (! $this->is_active) {
            return 'Kode diskon sedang tidak aktif.';
        }

        if ($this->start_date && now()->lt($this->start_date)) {
            return 'Kode diskon belum dapat digunakan (berlaku mulai '.$this->start_date->format('d/m/Y').').';
        }

        if ($this->end_date && now()->gt($this->end_date)) {
            return 'Kode diskon telah kedaluwarsa.';
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return 'Batas kuota penggunaan kode diskon ini telah habis.';
        }

        if ($this->min_order_amount > 0 && $subtotal < $this->min_order_amount) {
            return 'Minimal subtotal belanja untuk kode ini adalah Rp '.number_format($this->min_order_amount, 0, ',', '.');
        }

        return null;
    }

    public function calculateDiscount(float $subtotal): int
    {
        if (! $this->isValidForSubtotal($subtotal)) {
            return 0;
        }

        if ($this->type === 'percentage') {
            $discount = (int) round($subtotal * ($this->value / 100));
            if ($this->max_discount_amount && $this->max_discount_amount > 0) {
                $discount = min($discount, (int) round($this->max_discount_amount));
            }

            return min((int) $subtotal, $discount);
        }

        // Fixed amount
        return min((int) $subtotal, (int) round($this->value));
    }

    /**
     * Calculate discount strictly for a list of items, enforcing:
     * 1. Cannot be used on already discounted products
     * 2. Only applicable to specific products if restricted
     */
    public function calculateDiscountForItems(array $items, ?string &$reason = null, ?float &$eligibleSubtotalOut = null): int
    {
        $eligibleSubtotalOut = 0;

        if (! $this->is_active) {
            $reason = 'Kode diskon sedang tidak aktif.';

            return 0;
        }

        if ($this->start_date && now()->lt($this->start_date)) {
            $reason = 'Kode diskon belum dapat digunakan (berlaku mulai '.$this->start_date->format('d/m/Y').').';

            return 0;
        }

        if ($this->end_date && now()->gt($this->end_date)) {
            $reason = 'Kode diskon telah kedaluwarsa.';

            return 0;
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            $reason = 'Batas kuota penggunaan kode diskon ini telah habis.';

            return 0;
        }

        if (empty($items)) {
            $reason = 'Keranjang belanja kosong.';

            return 0;
        }

        $productIds = collect($items)->pluck('id')->filter()->unique()->toArray();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $allowedProductIds = null;
        if ($this->applies_to === 'specific_products') {
            $allowedProductIds = $this->products()->pluck('products.id')->toArray();
        }

        $eligibleSubtotal = 0;
        $hasAlreadyDiscountedItems = false;
        $hasRestrictedItems = false;

        foreach ($items as $item) {
            $productId = $item['id'] ?? null;
            $qty = max(1, (int) ($item['quantity'] ?? $item['qty'] ?? 1));
            $product = $productId ? ($products[$productId] ?? null) : null;

            $unitPrice = $product ? (float) $product->effective_price : (float) ($item['price'] ?? 0);

            // Check if product has admin-level discount
            if ($product && $product->hasDiscount()) {
                $hasAlreadyDiscountedItems = true;

                continue; // Cannot apply discount code to already discounted products
            }

            // Check specific product restriction
            if ($allowedProductIds !== null && ! in_array($productId, $allowedProductIds)) {
                $hasRestrictedItems = true;

                continue;
            }

            $eligibleSubtotal += ($unitPrice * $qty);
        }

        $eligibleSubtotalOut = $eligibleSubtotal;

        if ($eligibleSubtotal <= 0) {
            if ($hasAlreadyDiscountedItems && count($items) > 0 && ! $hasRestrictedItems) {
                $reason = 'Kode diskon/potongan harga tidak dapat digunakan pada produk yang sudah diskon.';
            } elseif ($this->applies_to === 'specific_products') {
                $reason = 'Kode diskon ini hanya berlaku untuk produk tertentu yang tidak ada di keranjang Anda.';
            } else {
                $reason = 'Tidak ada produk yang memenuhi syarat untuk menggunakan kode diskon ini.';
            }

            return 0;
        }

        if ($this->min_order_amount > 0 && $eligibleSubtotal < $this->min_order_amount) {
            $reason = 'Minimal subtotal produk memenuhi syarat untuk kode ini adalah Rp '.number_format($this->min_order_amount, 0, ',', '.');

            return 0;
        }

        $reason = null;

        if ($this->type === 'percentage') {
            $discount = (int) round($eligibleSubtotal * ($this->value / 100));
            if ($this->max_discount_amount && $this->max_discount_amount > 0) {
                $discount = min($discount, (int) round($this->max_discount_amount));
            }

            return min((int) $eligibleSubtotal, $discount);
        }

        // Fixed amount
        return min((int) $eligibleSubtotal, (int) round($this->value));
    }
}
