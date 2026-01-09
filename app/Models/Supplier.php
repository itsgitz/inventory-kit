<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Supplier extends Model
{
    use HasFactory;
    use HasUlids;
    use SoftDeletes;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'image',
    ];

    /*
    * The Supplier has many Product
    *
    * @return Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the supplier's initials
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
        $initials = $this->initials();
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=random&color=fff&size=128&bold=true&format=svg";
    }
}
