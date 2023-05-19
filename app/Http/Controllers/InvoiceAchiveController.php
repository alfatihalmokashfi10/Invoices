<?php

namespace App\Http\Controllers;

use App\Models\invoice;
use Illuminate\Http\Request;

use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceAchiveController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:ارشيف الفواتير', ['only' => ['index']]);

    }
    public function index()
    {
        $invoices=invoice::onlyTrashed()->get();
         $meta_description="برنامج الفواتير ";
        $meta_keywords=" قائمه الفواتير ,الفواتيرالمؤرشفه";

        return view('invoices.Archive',compact('invoices','meta_description','meta_keywords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = invoice::withTrashed()->where('id', $id)->restore();

        session()->flash('Restore');
        return redirect('/invoices');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = invoice::withTrashed()->where('id', $id)->first();
        $invoices->forceDelete();
        session()->flash('delete_invoice');
        return redirect('/invoices');
    }
}
