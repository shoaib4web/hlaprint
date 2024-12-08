<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $qry = Shop::query();

        $qry = $qry->with(['user', 'createdUser']);

        if ($request->isMethod('post')) {

            $qry->when($request->name, function ($query, $name) {
                return $query->where('name', $name);
            });

            $qry->when($request->contact, function ($query, $contact) {
                return $query->where('contact', $contact);
            });

            $qry->when($request->date, function ($query, $date) {
                return $query->whereDate('created_at', '<=', $date);
            });
        }

        if (Auth::user()->role == 'superadmin') {
            $qry->get();
        } elseif (Auth::user()->role == 'shopowner') {
            $qry->where('owner_id', Auth::user()->id);
        } else {
            $qry->where('owner_id', Auth::user()->id);
        }

        $data['shops'] = $qry->get();
        return view('admin.shop.index', compact('data'));
    }

    public function createShop(Request $request)
    {
        // print_r(Auth::user()->id);exit;
        $data['shop_owners'] = User::where('role', 'shopowner')->select('id', 'name')->get();
        $data['shops'] = Shop::where('owner_id',Auth::user()->id)->count();
        // echo  $data['shops'];exit;
        return view('admin.shop.create_shop', compact('data'));
    }

    public function storeShop(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'contact' => 'required',
            'owner_id' => 'required',
        ]);

        $shop = new Shop();
        $shop->user_id = Auth::user()->id;
        $shop->owner_id = $request->owner_id;
        $shop->name = $request->name;
        $shop->contact = $request->contact;
        $shop->address = $request->address;
        $res = $shop->save();

        if ($res) {
            // Find the user by owner_id
            $owner = User::find($request->owner_id);
            if ($owner) {
                // Update the shop_id of the owner
                $owner->shop_id = $shop->id;
                $owner->save();
            }
        }

        if ($res) {
            return redirect('shops')->with('success', 'Shop created successfully');
        } else {
            return back()->with('error', 'Shop Created Failed!');
        }
    }

    public function getWAofShop($shop_id)
    {
        $exists =  DB::table('whatsapp_shops')
        ->where('shop_id', $shop_id)
        ->get();

        if($exists)
        {
            return response()->json(['data' => $exists], 200);
        }
    }
    public function addWAToShop($shop_id, $wa_id)
    {
        // Check if the record already exists
        $exists = DB::table('whatsapp_shops')
                    ->where('shop_id', $shop_id)
                    ->where('WA_id', $wa_id)
                    ->exists();

        if (!$exists) {
            // Insert the record if it doesn't exist
            DB::table('whatsapp_shops')->insert([
                'shop_id' => $shop_id,
                'WA_id' => $wa_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return response()->json(['message' => 'Record added successfully.'], 200);
        } else {
            return response()->json(['message' => 'Record already exists.'], 409);
        }
    }



    public function updateShop(Request $request)
    {
        $shop = Shop::where('id', $request->edit_shop_id)->first();
        $shop->name = $request->name;
        $shop->contact = $request->contact;
        $shop->address = $request->address;
        $shop->owner_id = $request->owner_id;
        $res = $shop->update();


        if ($res) {
            // Find the user by owner_id
            $owner = User::find($request->owner_id);
            if ($owner) {
                // Update the shop_id of the owner
                $owner->shop_id = $shop->id;
                $owner->save();
            }
        }
        if ($res) {
            return redirect('shops')->with('success', 'Shop updated successfully');
        } else {
            return back()->with('error', 'Failed, Shop not update!');
        }
    }

    public function deleteUnused()
    {
     $whatsappShops = DB::table('whatsapp_shops')->get();

foreach ($whatsappShops as $shop) {
    $shopId = $shop->shop_id;

    // Check if the actual shop exists in the 'shops' table
    $shopExists = Shop::where('id', $shopId)->first();

    if (!$shopExists) {
        // If shop does not exist, delete the record in 'whatsapp_shops' table
        DB::table('whatsapp_shops')
            ->where('shop_id', $shopId)
            ->delete();
    }
}

// Optionally, return a response after processing all records
return response()->json(['message' => 'Records processed successfully'], 200);
    }

    public function deleteShop(Request $request)
    {
        $shop = Shop::where('id', $request->id)->delete();
        if ($shop) {
            return response()->json([
                'success' => true,
                'msg' => 'Shop Deleted Successfuly',
            ]);
        }
    }

    public function deleteWAfromShop($shop_id)
    {
        $exists =  DB::table('whatsapp_shops')
        ->where('shop_id', $shop_id)
        ->delete();
        return response()->json(['message' => 'Records processed successfully'], 200);
    }
}
