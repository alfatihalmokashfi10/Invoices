<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;
use App\Models\sections;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    function __construct()
    {
         $this->middleware('permission:الاقسام', ['only' => ['index']]);
         $this->middleware('permission:اضافة قسم', ['only' => ['create','store']]);
         $this->middleware('permission:تعديل قسم', ['only' => ['edit','update']]);
         $this->middleware('permission:حذف قسم', ['only' => ['destroy']]);
    }
    public function index()
    {$sections = sections::all();

        $products=product::all();
        return view ('products.products',compact('products','sections'));

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
        product::create([
            'product_name' => $request->product_name,
            'sections_id' => $request->section_id,
            'description' => $request->description,
        ]);
        session()->flash('Add', 'تم اضافة المنتج بنجاح ');
        return redirect('/products');

    }

    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'product_name' => 'required|max:255',
        ],[

            'product_name.required' =>'يرجي ادخال اسم المنتج',
            'product_name.max' =>'اسم المنتج  كبير',


        ]);

        $id = product::where('id', $request->id)->first();

        $id->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
        ]);
        session()->flash('Add', 'تم تعديل المنتج بنجاح ');
        return redirect('/products');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $id = $request->id;
        product::find($id)->delete();
        session()->flash('delete','تم حذف القسم بنجاح');
        return redirect('/products');
    }
}
