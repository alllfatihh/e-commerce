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
}
