<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'code',
        'description',
        'unit_price',
        'current_stock',
    ];

    /*
    * Product's category.
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /*
    * Product's supplier.
    *
    * @return Illuminate\Database\Eloquent\Relations\BelongsTo
    */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /*
    * Product's stock movements.
    *
    * @return Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
