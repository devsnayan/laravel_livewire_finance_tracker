<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\LedgerStatus;
use App\TransactionType;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ledger extends Model
{
    protected $fillable = [
        'user_id',
        'ledger_type_id',
        'name',
        'title',
        'notes',
        'opened_at',
        'is_star',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'closed_at' => 'datetime',
            'is_star' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ledgerType(): BelongsTo
    {
        return $this->belongsTo(LedgerType::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class)->latest();
    }

    public function totalCredit(): float
    {
        return (float) $this->transactions()
            ->where('trx_type', 'credit')
            ->with('items')
            ->get()
            ->sum(fn ($transaction) => $transaction->items->sum('amount'));
    }

    public function totalDebit(): float
    {
        return (float) $this->transactions()
            ->where('trx_type', 'debit')
            ->with('items')
            ->get()
            ->sum(fn ($transaction) => $transaction->items->sum('amount'));
    }

    public function balance(): float
    {
        return $this->totalCredit() - $this->totalDebit();
    }
    
}
