<?php

namespace App\Http\Controllers;

use App\Models\invoice;
use App\Models\sections;
use Illuminate\Http\Request;

class Customers_ReportController extends Controller
{
    public function index(){
        $meta_description="برنامج الفواتير ";
        $meta_keywords=" الاقسام ,الفواتير";
      $sections = sections::all();
      return view('reports.customers_report',compact('sections','meta_description','meta_keywords'));

    }


    public function Search_customers(Request $request){


// في حالة البحث بدون التاريخ

     if ($request->Section && $request->product && $request->start_at =='' && $request->end_at=='') {


      $invoices = invoice::select('*')->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
      $sections = sections::all();
       return view('reports.customers_report',compact('sections'))->withDetails($invoices);


     }


  // في حالة البحث بتاريخ

     else {

       $start_at = date($request->start_at);
       $end_at = date($request->end_at);

      $invoices = invoice::whereBetween('invoice_Date',[$start_at,$end_at])->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
       $sections = sections::all();
       return view('reports.customers_report',compact('sections'))->withDetails($invoices);


     }



    }
}
