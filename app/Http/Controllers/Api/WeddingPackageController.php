<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\WeddingPackage;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\WeddingPackageResource;

class WeddingPackageController extends Controller
{

    public function index()
    {
        $weddingPackages = WeddingPackage::with(['city', 'weddingOrganizer'])->get();

        foreach ($weddingPackages as $weddingPackage) {
            $weddingPackage->weddingOrganizer->loadCount('weddingPackages');
            $weddingPackage->city->loadCount('weddingPackages');
        }
        return WeddingPackageResource::collection($weddingPackages);
    }

    public function show(WeddingPackage $weddingPackage)
    {
        // Ensure the relationship is loaded properly
        $weddingPackage->load([
            'city',
            'photos',
            'weddingBonusPackages.bonusPackage', // Correct relationship path
            'weddingOrganizer',
            'weddingTestimonials',
        ]);


        $weddingPackage->weddingOrganizer->loadCount('weddingPackages');
        $weddingPackage->city->loadCount('weddingPackages');

        return new WeddingPackageResource($weddingPackage);
    }



    public function popular(Request $request)
    {
        $limit = $request->query('limit', 10); // Default to 10 if not specified

        $popularWeddingPackages = WeddingPackage::where('is_popular', true)
            ->with(['city', 'weddingOrganizer'])
            ->limit($limit)
            ->get();

        foreach ($popularWeddingPackages as $weddingPackage) {
            $weddingPackage->weddingOrganizer->loadCount('weddingPackages');
            $weddingPackage->city->loadCount('weddingPackages');
        }

        return WeddingPackageResource::collection($popularWeddingPackages);
    }
}
