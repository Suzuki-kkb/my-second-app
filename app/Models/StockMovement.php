<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    //
        // 設計書では updated_at がなく created_at のみ
    const UPDATED_AT = null;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'quantity',
        'memo',
        'created_at'
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
