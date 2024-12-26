<?php

namespace App\Models;

use App\Models\WeddingPackage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeddingTestimonial extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wedding_package_id',
        'name',
        'message',
        'photo',
        'occupation',
    ];

    public function weddingPackages(): BelongsTo
    {
        return $this->belongsTo(WeddingPackage::class, 'wedding_package_id');
    }

}
