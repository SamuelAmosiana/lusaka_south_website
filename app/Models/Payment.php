<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Payment extends Model
{
    public const PENDING   = 'pending';
    public const CONFIRMED = 'confirmed';
    public const REJECTED  = 'rejected';

    protected $fillable = [
        'transaction_reference', 'payer_name', 'phone_number', 'amount', 'status',
        'category', 'description', 'swish_trans_link_id', 'swish_method',
        'provider_transaction_id', 'paid_at', 'confirmed_via', 'notes',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public static function newReference(): string
    {
        return 'LSUC' . now()->format('Ymd') . strtoupper(Str::random(10));
    }

    public function logs(): HasMany
    {
        return $this->hasMany(PaymentLog::class, 'transaction_reference', 'transaction_reference')->latest('id');
    }

    public function isPending(): bool
    {
        return $this->status === self::PENDING;
    }

    public function markConfirmed(?string $providerTransactionId, string $via, mixed $raw = null): void
    {
        $this->update([
            'status'                  => self::CONFIRMED,
            'provider_transaction_id' => $providerTransactionId ?: $this->provider_transaction_id,
            'paid_at'                 => $this->paid_at ?? now(),
            'confirmed_via'           => $via,
        ]);
        $this->log('confirmed', ['via' => $via, 'data' => $raw]);
    }

    public function markRejected(string $via, mixed $raw = null): void
    {
        $this->update(['status' => self::REJECTED, 'confirmed_via' => $via]);
        $this->log('rejected', ['via' => $via, 'data' => $raw]);
    }

    public function log(string $action, mixed $raw = null): void
    {
        PaymentLog::create([
            'transaction_reference' => $this->transaction_reference,
            'raw_response'          => is_string($raw) ? $raw : json_encode($raw),
            'action_taken'          => $action,
        ]);
    }

    public function methodLabel(): string
    {
        return match ($this->swish_method) {
            'ussd'  => 'Mobile Money (USSD)',
            'push'  => 'Mobile Money (Push)',
            'qr'    => 'Mobile Money (QR)',
            'card'  => 'Card',
            default => ucfirst((string) $this->swish_method),
        };
    }
}
