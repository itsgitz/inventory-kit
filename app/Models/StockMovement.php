<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'user_id',
        'quantity',
        'reason',
        'type',
        'notes',
    ];

    /*
    * Ensure whenever a StockMovement is created, the product stock automatically updates.
    */
    protected static function booted()
    {
        static::created(function (StockMovement $stockMovement) {
            if ($stockMovement->type === 'IN') {
                $stockMovement->product->increment('current_stock', $stockMovement->quantity);
            } else {
                $stockMovement->product->decrement('current_stock', $stockMovement->quantity);
            }
        });
    }

    /*
    * The StockMovement belongs to Product
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /*
    *
    * The StockMovement belongs to User
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
