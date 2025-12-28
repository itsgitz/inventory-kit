<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockMovement extends Model
{
    use HasUlids;
    use SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'quantity',
        'reason',
        'type',
        'notes',
    ];

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
