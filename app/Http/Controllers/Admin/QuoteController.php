<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function index()
    {
        return view('admin.quotes.index');
    }

    public function create()
    {
        return view('admin.quotes.create');
    }

    public function pdf(Quote $quote)
    {
        $pdf= Pdf::loadView('admin.quotes.pdf', compact('quote'));
        return $pdf->download("Cotización-{$quote->id}.pdf");
        
    }
}
