<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        'image',
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

    /**
     * Get the product's initials
     */
    public function initials(): string
    {
        return strtoupper(
            Str::of($this->name)
                ->explode(' ')
                ->take(2)
                ->map(fn ($word) => Str::substr($word, 0, 1))
                ->implode('')
        );
    }

    /**
     * Get the image URL or avatar URL
     */
    public function getImageUrl(): string
    {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        // Generate avatar from name using UI Avatars
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=random&color=fff&size=128&bold=true&format=svg";
    }
}
