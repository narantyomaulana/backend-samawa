<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\WeddingOrganizer;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\WeddingOrganizerResource;

class WeddingOrganizerController extends Controller
{

    //
    public function index()
    {
        $weddingOrganizers = WeddingOrganizer::withCount('weddingPackages')->get();
        return WeddingOrganizerResource::collection($weddingOrganizers);
    }
    public function show(WeddingOrganizer $weddingOrganizer) // model binding
    {
        $weddingOrganizer->load([
            'weddingPackages.photos',
            'weddingPackages.weddingOrganizer' => function ($query) {
                $query->withCount('weddingPackages');
            }
        ])->loadCount('weddingPackages');
        return new WeddingOrganizerResource($weddingOrganizer);
    }
}
