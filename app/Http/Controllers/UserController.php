<?php

namespace App\Http\Controllers;

use App\Models\Color_size;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Shop;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $qry = User::query();

        if ($request->isMethod('post')) {

            $qry->when($request->email, function ($query, $email) {
                return $query->where('email', $email);
            });

            $qry->when($request->date, function ($query, $date) {
                return $query->whereDate('created_at', '>=', $date);
            });
        }

        if (Auth::user()->role == 'superadmin') {
            $qry->get();
        } elseif (Auth::user()->role == 'shopowner') {

            $qry->where('id', Auth::user()->id);
        } else {
            $qry->where('id', Auth::user()->id);
        }

        $data['users'] = $qry->get();
        return view('admin.user.index', compact('data'));
    }

    public function createUser()
    {
        return view('admin.user.create_user');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required',
        ]);

        $check_user = User::where('email', $request->email)->first();
        if (!$check_user) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = $request->role;
            $user->shop_id = $request->shop_id;
            $res = $user->save();
            if ($res) {
                return redirect('users')->with('success', 'User created successfully');
            } else {
                return back()->with('error', 'Failed, User not save!');
            }
        } else {
            return back()->with('error', 'This Email already exist!');
        }
    }

    public function editUser(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        return view('admin.user.edit_user', compact('user'));
    }

    public function updateUser(Request $request)
    {
        $user = User::where('id', $request->edit_user_id)->first();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $res = $user->update();
        if ($res) {
            return redirect('users')->with('success', 'User updated successfully');
        } else {
            return back()->with('error', 'Failed, User not update!');
        }
    }

    public function assignShop($id)
    {
        // Fetch the user by ID
    $user = User::where('id', $id)->first();

    // Fetch all shops
    $shops = Shop::all(['id', 'name']);

    // Pass the user and shops to the view
    return view('admin.user.assign_shop', compact('user', 'shops'));
    }

    public function updatePassword($userId,$password)
    {
        $user = User::where('id', $userId)->first();
        $user->password = Hash::make($password);
        $res = $user->update();
    }

    public function storeAssignedShop(Request $request)
{
    $user = User::find($request->edit_user_id);
    $user->shop_id = $request->shop_id;
    $user->save();

    return redirect()->back()->with('success', 'Shop assigned to user successfully.');
}


public function deleteUser($id)
{
    $user = User::where('id', $id)->first();
    if($user)
    {
        $user->delete();
    } else {
        return "User not Found";
    }
}

    public function colorSize()
    {

        if (Auth::user()->role == 'superadmin') {
            $data['color_size'] = Color_size::orderBy('id', 'desc')->get();
        }else{
            $newdata =[];
            $shops = Shops::where('owner_id',Auth::user()->id)->get();
            if($shops){
                foreach($shops as $s){
                    $r  = Color_size::orderBy('id', 'desc')->where('shop_id',$s->id)->get();
                    $newdata = $r;
                    // array_push( $newdata,$r);
                    // print_r($newdata);
                }
                $data['color_size'] =  $newdata ;
                //  print_r($data['color_size']);exit;
            }
        }


        return view('admin.color_size.index', compact('data'));
    }



    public function createColorSize($shop_id)
    {
        if($shop_id == 'admin')
        {
            $shop_id = 0;
            $data['shops'] = '';

        }else{
            $shop_id = Auth::user()->id;
            // echo $shop_id;exit;
            $data['shops'] = Shops::select('id', 'name')->where('owner_id',$shop_id)->get();
            // print_r($data['shops']);exit;

        }

        return view('admin.color_size.create_color_size' , compact('data','shop_id'));
    }

    public function storeColorSize(Request $request)
    {
        // echo $request->shop_id;exit;
        $color_size = Color_size::where('shop_id', '=', $request->shop_id)->first();
        if(!$color_size)
        {
            $color_size = new Color_size();
        }
        $color_size->color_copies = $request->color_copies;
        $color_size->color_copies_price = $request->color_copies_price;

        $color_size->bw_copies = $request->bw_copies;
        $color_size->bw_copies_price = $request->bw_copies_price;

        $color_size->color_page_amount = $request->color_page_amount;
        $color_size->black_and_white_amount = $request->black_and_white_amount;
        $color_size->size_amount = serialize([
            'A1' => $request->a1,
            'A2' => $request->a2,
            'A3' => $request->a3,
            'A4' => $request->a4,
            'A5' => $request->a5,
            'A6' => $request->a6,
            'A7' => $request->a7,
            'A8' => $request->a8,
            'A9' => $request->a9,
            'A10' => $request->a10,
        ]);
        $color_size->one_side = $request->one_side;
        $color_size->double_side = $request->double_side;
        $color_size->shop_id = $request->shop_id;
        $res = $color_size->save();
        if ($res) {
            return redirect('color-size')->with('success', 'Color size added successfully');
        } else {
            return back()->with('error', 'Failed, Color size not addedd!');
        }
    }

    public function editColorSize(Request $request)
    {
        $color_size = Color_size::where('shop_id', 0)->first();
        return view('admin.color_size.edit_color_size', compact('color_size'));
    }

    public function editShopColorSize($id)
    {
        $color_size = Color_size::where('shop_id', $id)->first();
        return view('admin.color_size.edit_color_size', compact('color_size'));
    }
}
