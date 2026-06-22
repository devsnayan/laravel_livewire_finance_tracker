<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\TransactionType;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'ledger_id',
        'date',
        'trx_type',
        'notes',
        'payment_method_id',
        'ref_no',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'trx_type' => TransactionType::class,
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ledger(): BelongsTo
    {
        return $this->belongsTo(Ledger::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function total_credit(): float
    {
        return $this->items()->where('trx_type', TransactionType::Credit)->sum('amount');
    }

    public function total_debit(): float
    {
        return $this->items()->where('trx_type', TransactionType::Debit)->sum('amount');
    }

    public function balance(): float
    {
        return $this->total_credit() - $this->total_debit();
    }

    
}
