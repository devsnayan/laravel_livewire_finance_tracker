<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Link extends Model
{
    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'notes',
        'url',
        'icon',
        'is_star',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_star' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(LinkCategory::class, 'category_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
