<?php

namespace App\Http\Controllers;
use com;
use File;
use App\Models\User;
use App\Models\invoice;
use App\Models\sections;
use Illuminate\Http\Request;

use App\Models\invoices_details;
use App\Notifications\add_invoice;
use Illuminate\Support\Facades\DB;
use Mockery\Expectation;
use PhpParser\Node\Stmt\Function_;
use App\Models\invoice_attachments;
use App\Notifications\add_inoice_db;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class InvoiceController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:قائمة الفواتير', ['only' => ['index']]);
         $this->middleware('permission:اضافة فاتورة', ['only' => ['create','store']]);
         $this->middleware('permission:تعديل الفاتورة', ['only' => ['edit','update']]);
         $this->middleware('permission:حذف الفاتورة', ['only' => ['destroy']]);
         $this->middleware('permission:الفواتير المدفوعة', ['only' => ['invoice_paid']]);
         $this->middleware('permission:الفواتير الغير مدفوعة', ['only' => ['invoice_unpaid']]);
         $this->middleware('permission:الفواتير المدفوعة جزئيا', ['only' => ['invoice_partial']]);
         $this->middleware('permission:طباعةالفاتورة', ['only' => ['Print_invoice']]);
    }
    public function index(){
    $meta_description="برنامج الفواتير ";
    $meta_keywords=" قائمه الفواتير ,جميع الفواتير ";
     $invoices=invoice::all();

        return view('invoices.invoices',compact('invoices','meta_description','meta_keywords'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $sections = sections::all();
        return view('invoices.add_invoice', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

            $validatedData = $request->validate([
                'invoice_number' => 'required|max:255',
                'product' => 'required|max:255',
                'Section' => 'required|max:255',

                'Amount_collection' => 'required|max:8',
                'Amount_Commission' => 'required|max:255',
            ],[

                'invoice_number.required' =>'يرجي ادخال رقم الفاتوره القسم',
                'product.required' =>'يرجي اختيار اسم المنتج',
                'Section.required' =>'يرجي اختيار اسم القسم',
                'Amount_collection.required' =>'يرجي ادخال مبلغ التحصيل ',
                'Amount_collection.max' =>'يرجي ادخال مبلغ تحصيل اقل ',
                'Amount_Commission.required' =>'يرجي ادخال مبلغ العموله',



            ]);
            invoice::create([
                'invoice_number' => $request->invoice_number,
                'invoice_Date' => $request->invoice_Date,
                'Due_date' => $request->Due_date,
                'product' => $request->product,
                'section_id' => $request->Section,
                'Amount_collection' => $request->Amount_collection,
                'Amount_Commission' => $request->Amount_Commission,
                'Discount' => $request->Discount,
                'Value_VAT' => $request->Value_VAT,
                'Rate_VAT' => $request->Rate_VAT,
                'Total' => $request->Total,
                'Status' => 'غير مدفوعة',
                'Value_Status' => 2,
                'note' => $request->note,
            ]);



            $invoice_id = invoice::latest()->first()->id;
            invoices_details::create([
                'id_Invoice' => $invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => 'غير مدفوعة',
                'Value_Status' => 2,
                'note' => $request->note,
                'user' => (Auth::user()->name),
            ]);

            if ($request->hasFile('pic')) {

                $invoice_id = Invoice::latest()->first()->id;
                $image = $request->file('pic');
                $file_name = $image->getClientOriginalName();
                $invoice_number = $request->invoice_number;

                $attachments = new invoice_attachments();
                $attachments->file_name = $file_name;
                $attachments->invoice_number = $invoice_number;
                $attachments->Created_by = Auth::user()->name;
                $attachments->invoice_id = $invoice_id;
                $attachments->save();

                // move pic
                $imageName = $request->pic->getClientOriginalName();
                $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
            }

$user=User::get();
            $invoice = Invoice::latest()->first();
                Notification::send($user, new add_inoice_db($invoice));












            session()->flash('add_invoice');
            return redirect('/invoices');
        // }
        // catch (\Exception $ex) {

        //     return back()->with('Add', 'حاول لاحقا');;
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $invoices = invoice::where('id', $id)->first();
        return view('invoices.status_update', compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {        $id = $request->invoice_id;
        $invoices = invoice::where('id', $id)->first();
        $Details = invoice_attachments::where('invoice_id', $id)->first();

         $id_page =$request->id_page;


        if (!$id_page==2) {

        if (!empty($Details->invoice_number)) {

            Storage::disk('public_uploads') ->deleteDirectory($Details->invoice_number);
        }
        $invoices->forceDelete();
        session()->flash('delete_invoice');
        return redirect('/invoices');

        }

        else {

            $invoices->delete();
            session()->flash('archive_invoice');
            return redirect('/Archive');
        }



    }
    public function getproducts($id)
    {
        $products = DB::table("productS")->where("sections_id", $id)->pluck("product_name", "id");
        return json_encode($products);
    }
    public function Status_Update($id, Request $request)
    {
        $invoices = invoice::findOrFail($id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }

        else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/invoices');

    }
    public Function invoice_paid(){
        $meta_description="برنامج الفواتير ";
        $meta_keywords=" قائمه الفواتير ,الفواتير المدفوعه";
        $invoices = Invoice::where('Value_Status', 1)->get();
        return view('invoices.invoice_paid',compact('invoices','meta_description','meta_keywords'));

    }
    public Function invoice_unpaid(){
        $meta_description="برنامج الفواتير ";
        $meta_keywords=" قائمه الفواتير ,الفواتير الغير مدفوعه";
     $invoices = Invoice::where('Value_Status',2)->get();
    return view('invoices.invoice_unpaid',compact('invoices','meta_description','meta_keywords'));
}
    public Function invoice_partial(){
        $meta_description="برنامج الفواتير ";
        $meta_keywords=" قائمه الفواتير ,الفواتير المدفوعه جزئيا";
        $invoices = Invoice::where('Value_Status',3)->get();
        return view('invoices.invoice_Partial',compact('invoices','meta_description','meta_keywords'));
    }
    public Function Print_invoice($id){
        $meta_description="برنامج الفواتير ";
        $meta_keywords=" قائمه الفواتير ,طباعه الفواتير";
$invoices=invoice::where('id',$id)->first();
return view('invoices.print_invoice',compact('invoices','meta_description','meta_keywords'));
    }
    public Function export(){


   //  return Excel::download(new InvoicesExport, 'invoices.xlsx');
    }
    public Function noti_read(){

    $userunreadNotifications = auth()->user()->unreadNotifications;

if ($userunreadNotifications) {
    $userunreadNotifications->markAsRead();
    return back();
}}
}
