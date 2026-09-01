<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transfer;
use Barryvdh\DomPDF\Facade\Pdf;

class TransferController extends Controller
{
    public function index()
    {
        return view('admin.transfers.index');
    }

    public function create()
    {
        return view('admin.transfers.create');
    }

      public function pdf(Transfer $transfer)
    {
        $pdf= Pdf::loadView('admin.transfers.pdf', compact('transfer'));
        return $pdf->download("Transferencia-{$transfer->id}.pdf");
        
    }
}
