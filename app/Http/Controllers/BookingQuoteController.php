<?php

namespace App\Http\Controllers;

use App\Enums\CommercialdocStatus;
use App\Models\Commercialdoc;
use Illuminate\Http\Request;

class BookingQuoteController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // TODO: Move code from ReservationController::show()
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: Move code from ReservationController::confirmReservation()
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // TODO: Move code from ReservationController::showQuote()
        // For now, check if create_at < date_sub(now, interval 6 months)
        // if so, show 404 error message: "Sorry, quotes and invoices older than 6 months are destroyed."

        // TODO: If final quote is sent, also handle showing validate button
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Commercialdoc $doc, Request $request)
    {
        // handle validation and
        $doc->update(["status" => CommercialdocStatus::FINAL_QUOTE_SENT]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
