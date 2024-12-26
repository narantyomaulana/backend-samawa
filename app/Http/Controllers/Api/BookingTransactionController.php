<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\WeddingPackage;
use App\Models\BookingTransaction;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingTransactionRequest;
use App\Http\Resources\Api\BookingTransactionResource;

class BookingTransactionController extends Controller
{

    public function store (StoreBookingTransactionRequest $request)
    {
        $validatedData = $request->validated();

        $weddingPackage = WeddingPackage::find($validatedData['wedding_package_id']);

        if (!$weddingPackage) {
            return response()->json(['message' => 'Wedding package not found'], 404);
        }

        // Handle file upload
        if ($request->hasFile('proof')) {
            $filePath = $request->file('proof')->store('payment/proofs', 'public');
            $validatedData['proof'] = $filePath;
        }

        $price  = $weddingPackage->price;
        $tax = 0.11;
        $totalTax = $tax * $price;
        $grandTotal = $price + $tax;


        $validatedData['price'] = $price;
        $validatedData['total_amount'] = $grandTotal;
        $validatedData['total_tax_amount'] = $totalTax;

        $validatedData['is_paid'] = false;

         // Create a new instance and call the method
        $bookingTransaction = new BookingTransaction();
        $validatedData['booking_trx_id'] = $bookingTransaction->generateUniqueTrxId();

        $bookingTransaction = BookingTransaction::create($validatedData);

        $bookingTransaction->load('weddingPackage');

        return new BookingTransactionResource($bookingTransaction);
    }


    public function booking_details(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'booking_trx_id' => 'required|string',
        ]);

        $booking = BookingTransaction::where('phone', $request->phone)
            ->where('booking_trx_id', $request->booking_trx_id)
            ->with([
                'weddingPackage',
                'weddingPackage.city' => function ($query) {
                    $query->withCount('weddingPackages');
                },
                'weddingPackage.weddingBonusPackages.bonusPackage',
                'weddingPackage.weddingOrganizer' => function ($query) {
                    $query->withCount('weddingPackages');
                }
            ])
            ->first();

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return new BookingTransactionResource($booking);
    }
}
