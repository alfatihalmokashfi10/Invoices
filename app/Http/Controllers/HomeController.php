<?php

namespace App\Http\Controllers;

use App\Models\invoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $meta_description="برنامج الفواتير ";
        $meta_keywords="الصفحه الرئيسيه,الفواتير";
        $invoices=invoice::count();
$invoices_paid=invoice::where('Value_Status',1)->count();
$invoices_unpaid=invoice::where('Value_Status',2)->count();
$invoices_partial=invoice::where('Value_Status',3)->count();
if($invoices>0){
$nsinvoices_paid=round(($invoices_paid/$invoices)*100);
$nsinvoices_unpaid=round(($invoices_unpaid/$invoices)*100);
$nsinvoices_partial=round(($invoices_partial/$invoices)*100);

        $chartjs = app()->chartjs
        ->name('barChartTest')
        ->type('bar')
        ->size(['width' => 350, 'height' => 200])
        ->labels(['الفواتير الغير مدفوعة ' ,'الفواتير  المدفوعة جزئياً ', ' الفواتير المدفوعة   '])
        ->datasets([


            [
                "label" => "نسبة الفواتير المدفوعة ",
                'backgroundColor' => ['#029666'],
                'data' => [$nsinvoices_paid]
            ],
            [
                "label" => "نسبة الفواتير المدفوعة جزئياً",
                'backgroundColor' => ['#f76a2d'],
                'data' => [$nsinvoices_partial]
            ],
            [
                "label" => "نسبة الفواتير الغير مدفوعة   ",
                'backgroundColor' => ['rgba(255, 99, 132, 3)'],
                'data' => [$nsinvoices_unpaid]
            ]

        ])
        ->options([]);
        $chartjs2 = app()->chartjs
        ->name('pieCharts')
        ->type('pie')
        ->size(['width' => 350, 'height' => 200])
        ->labels(['الفواتير المدفوعة ' ,'الفواتير  المدفوعة جزئياً ', ' الفواتير الغير مدفوعة'])
        ->datasets([
            [
                "label" => "نسبة الفواتير المدفوعة ","نسبة الفواتير المدفوعة جزئياً","نسبة الفواتير الغير مدفوعة   ",
                'backgroundColor' => ['#029666','#f76a2d','rgba(255, 99, 132, 3)'],
                'data' => [$nsinvoices_paid,$nsinvoices_partial,$nsinvoices_unpaid]
            ],

        ])->options([])
        ;

return view('home',compact('chartjs','chartjs2','meta_description','meta_keywords'));
        }
        else{
            return view('home');
        }

// example.blade.php
    }
}
