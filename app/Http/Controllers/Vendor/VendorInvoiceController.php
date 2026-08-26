<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\ResortBooking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class VendorInvoiceController extends Controller
{
    /**
     * Display invoice.
     */
    public function show(ResortBooking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->load([
            'user',
            'vendor',
            'resort',
            'room',
            'guests',
            'payments',
        ]);

        return view(
            'vendor.invoices.show',
            compact('booking')
        );
    }


    /**
     * Download invoice as PDF.
     */
    public function download(ResortBooking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->load([
            'user',
            'vendor',
            'resort',
            'room',
            'guests',
            'payments',
        ]);

        $pdf = Pdf::loadView(
            'vendor.invoices.pdf',
            compact('booking')
        );

        $pdf->setPaper(
            'a4',
            'portrait'
        );

        return $pdf->download(
            'invoice-' .
            $booking->booking_code .
            '.pdf'
        );
    }


    /**
     * Print invoice.
     */
    public function print(ResortBooking $booking)
    {
        $this->authorizeBooking($booking);

        $booking->load([
            'user',
            'vendor',
            'resort',
            'room',
            'guests',
            'payments',
        ]);

        return view(
            'vendor.invoices.print',
            compact('booking')
        );
    }


    /**
     * Authorize booking.
     */
    private function authorizeBooking(
        ResortBooking $booking
    ): void {

        $vendor = Auth::user()->vendor;

        abort_if(
            !$vendor,
            403,
            'Vendor profile not found.'
        );

        abort_unless(
            $booking->vendor_id === $vendor->id,
            403,
            'You are not authorized to access this invoice.'
        );
    }
}