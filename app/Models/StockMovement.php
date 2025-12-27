<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    * The StockMovement has many Product
    *
    * @return Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /*
    *
    * The StockMovement has many User
    *
    * @return Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
