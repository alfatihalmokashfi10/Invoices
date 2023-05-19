<?php

namespace App\Http\Controllers;

use App\Models\invoice;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    public function index(){
        $meta_description="برنامج الفواتير ";
        $meta_keywords="  التقارير ,تقارير الفواتير ";
        return view('reports.invoices_report',compact('meta_description','meta_keywords'));

       }

       public function Search_invoices(Request $request){

       $rdio = $request->rdio;




       if ($rdio == 1) {



           if ($request->type && $request->start_at =='' && $request->end_at =='') {

              $invoices = invoice::select('*')->where('Status','=',$request->type)->get();
              $type = $request->type;
              return view('reports.invoices_report',compact('type'))->with($invoices);
           }

           else {

             $start_at = date($request->start_at);
             $end_at = date($request->end_at);
             $type = $request->type;

             $invoices = invoice::whereBetween('invoice_Date',[$start_at,$end_at])->where('Status','=',$request->type)->get();
             return view('reports.invoices_report',compact('type','start_at','end_at'))->with($invoices);

           }



       }

   //====================================================================

   // في البحث برقم الفاتورة
       else {

           $invoices = invoice::select('*')->where('invoice_number','=',$request->invoice_number)->get();
           return view('reports.invoices_report')->with($invoices);

       }



       }



}
