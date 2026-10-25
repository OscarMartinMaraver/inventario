<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return view('admin.purchase_orders.index');
    }

    public function create()
    {
        return view('admin.purchase_orders.create');
    }

    public function pdf(PurchaseOrder $purchaseOrder)
    {
        $pdf= Pdf::loadView('admin.purchase_orders.pdf', [
            'model'=>$purchaseOrder,
        ]);
        return $pdf->download("Orden_de_Compra-{$purchaseOrder->id}.pdf");
        
    }
}
