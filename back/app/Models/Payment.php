<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'registration_id', 'type', 'tripay_reference', 'merchant_ref',
        'method', 'amount', 'status', 'tripay_response', 'paid_at', 'expired_at',
        'payment_url', 'proof_of_payment'
    ];

    protected $casts = [
        'amount' => 'decimal:0',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function registration() { return $this->belongsTo(Registration::class); }

    public function isPaid(): bool { return $this->status === 'paid'; }
    public function isUnpaid(): bool { return $this->status === 'unpaid'; }
    public function isExpired(): bool { return $this->status === 'expired'; }
}
