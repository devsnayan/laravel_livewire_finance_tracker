<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class Ledger extends Model
{
    protected $fillable = [
        'user_id',
        'ledger_type_id',
        'name',
        'title',
        'notes',
        'status',
        'opened_at',
        'closed_at',
        'is_star',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'status' => LedgerStatus::class,
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
}
