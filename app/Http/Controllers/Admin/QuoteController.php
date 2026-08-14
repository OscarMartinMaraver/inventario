<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
