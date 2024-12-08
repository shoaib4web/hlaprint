<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PriceOptions;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PriceOptionsController extends Controller
{
    //// Display a listing of the resource
    public function index()
    {
        $data['priceOptions'] = PriceOptions::where('shop_id', Auth::user()->shop_id)->get();
        return view('admin.price_options.index', compact('data'));
    }
    
    public function create()
    {
        return view('admin.price_options.create');
    }
    
   public function store(Request $request)
{
    // Check if a record with the same page_size, color_type, and sidedness already exists
    $exists = PriceOptions::where('page_size', $request->page_size)
                          ->where('color_type', $request->color_type)
                          ->where('sidedness', $request->sidedness)
                          ->where('shop_id',Auth::user()->shop_id)
                          ->exists();

    if ($exists) {
        // Return an error response, e.g., redirect back with an error message
        
        return view('admin.price_options.create')->with('test', 'Combination Already Exists');
        //return back()->withErrors(['msg' => 'A record with the same page size, color type, and sidedness already exists.']);

    }

    // If the record does not exist, create a new PriceOptions instance and save it
    $priceOptions = new PriceOptions();
    $priceOptions->page_size = $request->page_size;
    $priceOptions->color_type = $request->color_type;
    $priceOptions->sidedness = $request->sidedness;
    $priceOptions->no_of_pages = $request->no_of_pages;
    $priceOptions->shop_id = Auth::user()->shop_id;
    $priceOptions->base_price = $request->base_price;
    $priceOptions->save();

    // Redirect to a desired location, e.g., the index view with a success message
    return redirect()->route('price-options')->with('success', 'Record saved successfully.');
}


public function edit($id)
{
    $priceOptions = PriceOptions::find($id);
    return view('admin.price_options.edit', compact('priceOptions'));
}

public function update(Request $request)
{
    // Validate the request data as necessary
    $exists = PriceOptions::where('page_size', $request->page_size)
                          ->where('color_type', $request->color_type)
                          ->where('sidedness', $request->sidedness)
                          ->where('shop_id', Auth::user()->shop_id)
                          ->first();
    if($exists)
    {
        $exists = $request->id != $exists->id;
    }

    if($exists)
    {
        
        return redirect()->back()->with('test', 'Combination Already Exists');
    }

    // Step 1: Retrieve the record
    $priceOptions = PriceOptions::findOrFail($request->id); // or use find() and handle the case if null

    // Step 2: Update the record
    $priceOptions->page_size = $request->page_size;
    $priceOptions->color_type = $request->color_type;
    $priceOptions->sidedness = $request->sidedness;
    $priceOptions->no_of_pages = $request->no_of_pages;
    $priceOptions->shop_id = $request->shop_id;
    $priceOptions->base_price = $request->base_price;

    // Step 3: Save the changes
    $priceOptions->save();

    // Redirect or return response
    return redirect()->route('price-options')->with('success', 'Record updated successfully');
}
}
