<?php

namespace App\Models;

use App\Models\City;
use Illuminate\Support\Str;
use App\Models\WeddingPhoto;
use App\Models\WeddingOrganizer;
use App\Models\WeddingTestimonial;
use App\Models\WeddingBonusPackage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeddingPackage extends Model
{
    use HasFactory, SoftDeletes;


    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'about',
        'price',
        'is_popular',
        'is_active',
        'city_id',
        'wedding_organizer_id',
    ];

    // Laravel Aceessor
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function weddingOrganizer(): BelongsTo
    {
        return $this->belongsTo(WeddingOrganizer::class, 'wedding_organizer_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(WeddingPhoto::class);
    }

    public function weddingBonusPackages(): HasMany
    {
        return $this->hasMany(WeddingBonusPackage::class, 'wedding_package_id');
    }

    public function weddingTestimonials(): HasMany
    {
        return $this->hasMany(WeddingTestimonial::class);
    }
}
