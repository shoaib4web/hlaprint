<?php

namespace App\Http\Controllers;

use App\Models\FinancialDetails;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialDetailsController extends Controller
{
    // Show the form to create financial details for a shop
    public function load(Request $request)
    {
        $shopId = Auth::user()->shop_id;
        $shop = Shop::find($shopId);
        if($shop->financialDetails)
        {
            return view('admin.financial-details.index')->with('financialDetails',$shop->financialDetails);
        }
        else {
            return view('admin.financial-details.index');
        }
    }

    // Store the financial details for a shop
    public function store(Request $request)
    {
        $shopId = Auth::user()->shop_id;
        $shop = Shop::find($shopId);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'bank' => 'required|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'email' => 'required|email',
            'mobile_number' => 'required|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
        ]);

        $financialDetails = new FinancialDetails($validated);
        $shop->financialDetails()->save($financialDetails);

        return redirect()->route('financial-details', $shop)->with('success', 'Financial Details created successfully.');
    }



    // Update the financial details for a shop
    public function update(Request $request, $financialDetailsId)
    {
        $financialDetails = FinancialDetails::find($financialDetailsId);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'bank' => 'required|string|max:255',
            'bank_branch' => 'nullable|string|max:255',
            'email' => 'required|email',
            'mobile_number' => 'required|string|max:255',
            'address_1' => 'required|string|max:255',
            'address_2' => 'nullable|string|max:255',
        ]);

        $financialDetails->update($validated);

        return redirect()->route('financial-details', $financialDetails)->with('success', 'Financial Details updated successfully.');
    }
}
