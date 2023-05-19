<?php

namespace App\Http\Controllers;

use File;
use App\Models\invoice;
use Illuminate\Http\Request;
use App\Models\invoices_details;
use Illuminate\Support\Facades\DB;
use App\Models\invoice_attachments;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class InvoicesDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( $id)
    {
        $meta_description="برنامج الفواتير ";
        $meta_keywords="  الفواتير ,تفاصيل الفواتير ";
        $invoices = invoice::where('id',$id)->first();
        $details  = invoices_Details::where('id_Invoice',$id)->get();
        $attachments  = invoice_attachments::where('invoice_id',$id)->get();


$n=DB::table('notifications')->where('data->invoice_id',$id)->where('notifiable_id',auth()->user()->id)->first()->id;

    DB::table('notifications')->where('id',$n)->update(['read_at' => now()]);

    return view('invoices.details_invoice',compact('invoices','details','attachments','meta_description','meta_keywords')); //
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
    public function show(invoices_details $invoices_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoices = invoice::where('id',$id)->first();
        $details  = invoices_Details::where('id_Invoice',$id)->get();
        $attachments  = invoice_attachments::where('invoice_id',$id)->get();

        return view('invoices.details_invoice',compact('invoices','details','attachments')); //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $invoices = invoice_attachments::findOrFail($request->id_file);
        $invoices->delete();
        $file_name=$request->file_name;
        Storage::disk('public_uploads')->delete($request->invoice_number.'/'.'/'.$file_name);
        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }


    public function get_file($invoice_number,$file_name)

    {
     return Storage::disk('public_uploads')->download($invoice_number.'/'.$file_name);
  return response()->download( $contents);
    }

    public function open_file($invoice_number,$file_name)

    {
        return Storage::disk('public_uploads')->get($invoice_number.'/'.$file_name);
      //  return response()->file($files);
    }
}
