<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\WeddingTestimonial;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\WeddingTestimonialResource;

class WeddingTestimonialController extends Controller
{
    public function index()
    {
        $limit = request()->query('limit', 10);

        // Menggunakan relasi yang sudah diperbaiki (weddingPackage, bukan weddingPackages)
        $weddingTestimonials = WeddingTestimonial::with('weddingPackages')
            ->limit($limit)
            ->get();

        return WeddingTestimonialResource::collection($weddingTestimonials);
    }


}
