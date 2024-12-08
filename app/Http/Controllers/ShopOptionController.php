<?php

namespace App\Http\Controllers;

use App\Models\ShopOption;
use Illuminate\Http\Request;

class ShopOptionController extends Controller
{
    /**
     * Show the form for editing the specified shop option.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $shopOption = ShopOption::findOrFail($id);
        return view('shop_options.edit', compact('shopOption'));
    }

    /**
     * Update the specified shop option in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'print_invoice' => 'required|boolean',
            'print_separator' => 'required|boolean',
        ]);

        $shopOption = ShopOption::findOrFail($id);
        $shopOption->update($request->only(['print_invoice', 'print_separator']));

        return redirect()->route('shop_options.edit', $shopOption->id)
                         ->with('success', 'Shop options updated successfully');
    }
}
