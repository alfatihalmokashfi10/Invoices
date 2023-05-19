<?php

namespace App\Http\Controllers;
use App\Models\invoice;
use App\Models\invoice_attachments;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
class InvoiceAttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( )
    {
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
    { $this->validate($request,
    [    'file_name'=>'mimes:pdf,jpeg,jpg,png',],
    );

    $file_name=$request->file('filename')->getClientOriginalName();
        $attachments = new invoice_attachments();
        $attachments->file_name = $file_name;
        $attachments->invoice_number = $request->invoice_number;

        $attachments->invoice_id = $request->invoice_id;
        $attachments->Created_by = Auth::user()->name;

        $attachments->save();

        // move pic
        $imageName = $request->filename->getClientOriginalName();
        $request->filename->move(public_path('Attachments/' . $request->invoice_number), $imageName);

       // $user = User::first();
       // Notification::send($user, new AddInvoice($invoice_id));



    session()->flash('Add', 'تم اضافة المرفق بنجاح');
    return back();

    }

    /**
     * Display the specified resource.
     */
    public function show(invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoice_attachments $invoice_attachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(invoice_attachments $invoice_attachments)
    {
        //
    }
}
