<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_trx_id',
        'name',
        'email',
        'phone',
        'proof',
        'total_amount',
        'price',
        'total_tax_amount',
        'is_paid',
        'started_at',
        'wedding_package_id',
    ];

    public function weddingPackage(): BelongsTo
    {
        return $this->belongsTo(WeddingPackage::class, 'wedding_package_id');
    }

    public static function generateUniqueTrxId()
    {
        $prefix = 'SMWA';

        do {
            $randomString = $prefix . mt_rand(100000, 999999);
        } while (self::where('booking_trx_id', $randomString)->exists());

        return $randomString;
    }

}
