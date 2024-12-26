<?php

namespace App\Models;

use App\Models\BonusPackage;
use App\Models\WeddingPackage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeddingBonusPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'wedding_package_id',
        'bonus_package_id',
    ];

    public function weddingPackage(): BelongsTo
    {
        return $this->belongsTo(WeddingPackage::class, 'wedding_package_id');
    }

    public function bonusPackage(): BelongsTo
    {
        return $this->belongsTo(BonusPackage::class, 'bonus_package_id');
    }


}
