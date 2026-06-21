<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class LinkCategory extends Model
{
    protected $fillable = [
        'user_id',
        'parent_id',
        'name',
        'icon',
        'caption',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(LinkCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(LinkCategory::class, 'parent_id');
    }
}
