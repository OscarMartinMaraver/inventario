<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseController extends Controller
{
    public function index()
    {
        return view('admin.purchases.index');
    }

    public function create()
    {
        return view('admin.purchases.create');
    }

    //PDF
    public function pdf(Purchase $purchase)
    {
        $pdf= Pdf::loadView('admin.purchases.pdf', [
            'model'=>$purchase,
            ]);
            
        return $pdf->download("Compra-{$purchase->id}.pdf");
        
    }
}
