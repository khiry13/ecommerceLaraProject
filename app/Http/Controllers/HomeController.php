<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

// use Session;
use Stripe;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::paginate(3);
        return view('home.userpage', compact('products'));
    }

    public function redirect()
    {
        $usertype = Auth::user()->usertype;

        if ($usertype == '1') {

            $total_products = Product::all()->count();
            $total_orders = Order::all()->count();
            $total_users = User::all()->count();
            $orders = Order::all();

            $total_revenue = 0;
            foreach ($orders as $order) {
                $total_revenue = $total_revenue + $order->price;
            }

            $total_deliverd = Order::where('delivery_status', '=', 'Deliverd')->get()->count();
            $total_processing = Order::where('delivery_status', '=', 'processing')->get()->count();
            return view('admin.home', compact('total_products', 'total_orders', 'total_users', 'total_revenue', 'total_deliverd', 'total_processing'));
        } else {
            $products = Product::paginate(3);
            return view('home.userpage', compact('products'));
        }
    }

    public function product_details($id)
    {
        $product = Product::find($id);
        return view('home.product_details', compact('product'));
    }

    public function add_cart(Request $request, $id)
    {
        if (Auth::id()) {
            $user = Auth::user();
            $userId = $user->id;

            $product = Product::find($id);
            $product_exist_id = Cart::where('product_id', '=', $id)->where('user_id', '=', $userId)->get('id')->first();

            if ($product_exist_id) {
                $cart = Cart::find($product_exist_id)->first();
                $quantity = $cart->quantity;
                $cart->quantity = $quantity + $request->quantity;

                if ($product->discount_price != null) {
                    $cart->price = $product->discount_price * $cart->quantity;
                } else {
                    $cart->price = $product->price * $cart->quantity;
                }

                $cart->save();
                Alert::success('Product Added Successfully', 'We have added product to the cart');

                return redirect()->back();
            } else {
                $cart = new Cart;

                $cart->name = $user->name;
                $cart->email = $user->email;
                $cart->phone = $user->phone;
                $cart->address = $user->address;
                $cart->user_id = $user->id;

                $cart->product_title = $product->title;
                if ($product->discount_price != null) {
                    $cart->price = $product->discount_price * $request->quantity;
                } else {
                    $cart->price = $product->price * $request->quantity;
                }
                $cart->image = $product->image;
                $cart->product_id = $product->id;

                $cart->quantity = $request->quantity;

                $cart->save();

                Alert::success('Product Added Successfully', 'We have added product to the cart');
                return redirect()->back();
            }
        } else {
            return redirect('login');
        }
    }

    public function show_cart()
    {
        if (Auth::id()) {
            $id = Auth::user()->id;
            $carts = Cart::where('user_id', '=', $id)->get();
            return view('home.showcart', compact('carts'));
        } else {
            return redirect('login');
        }
    }

    public function remove_cart($id)
    {
        $cart = Cart::find($id);

        $cart->delete();

        return redirect()->back();
    }

    public function cash_order()
    {
        $user_id = Auth::user()->id;
        $alldata = Cart::where('user_id', '=', $user_id)->get();

        foreach ($alldata as $data) {
            $order = new Order;

            $order->name = $data->name;
            $order->email = $data->email;
            $order->phone = $data->phone;
            $order->address = $data->address;
            $order->user_id = $data->user_id;

            $order->product_title = $data->product_title;
            $order->price = $data->price;
            $order->quantity = $data->quantity;
            $order->image = $data->image;
            $order->product_id = $data->product_id;

            $order->payment_status = 'cash on delivery';
            $order->delivery_status = 'processing';

            $order->save();

            $cart_id = $data->id;
            $cart = Cart::find($cart_id);
            $cart->delete();
        }

        return redirect()->back()->with('message', 'We have Recived Your Order. We will Connect with You Soon....');
    }

    public function show_order()
    {

        if (Auth::id()) {

            $userId = Auth::user()->id;
            $orders = Order::where('user_id', '=', $userId)->get();
            return view('home.order', compact('orders'));
        } else {
            return redirect('login');
        }
    }

    public function cancel_order($id)
    {
        $order = Order::find($id);

        $order->delivery_status = 'You canceled this order';

        $order->save();

        return redirect()->back();
    }

    public function product_search(Request $request)
    {
        $searchtext = $request->search;

        $products = Product::where('title', 'LIKE', "%$searchtext%")->orWhere('title', 'LIKE', "%$searchtext%")->paginate(10);

        return view('home.userpage', compact('products'));
    }

    public function stripe($totalprice)
    {

        return view('home.stripe', compact('totalprice'));
    }

    public function stripePost(Request $request, $totalprice)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        Stripe\Charge::create([
            "amount" => $totalprice * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Test payment from itsolutionstuff.com."
        ]);

        $user_id = Auth::user()->id;
        $alldata = Cart::where('user_id', '=', $user_id)->get();

        foreach ($alldata as $data) {
            $order = new Order;

            $order->name = $data->name;
            $order->email = $data->email;
            $order->phone = $data->phone;
            $order->address = $data->address;
            $order->user_id = $data->user_id;

            $order->product_title = $data->product_title;
            $order->price = $data->price;
            $order->quantity = $data->quantity;
            $order->image = $data->image;
            $order->product_id = $data->product_id;

            $order->payment_status = 'Paid';
            $order->delivery_status = 'processing';

            $order->save();

            $cart_id = $data->id;
            $cart = Cart::find($cart_id);
            $cart->delete();
        }

        Session::flash('success', 'Payment successful!');

        return back();
    }

    public function product()
    {
        $products = Product::paginate(3);
        return view('home.all_products', compact('products'));
    }

    public function search_product(Request $request)
    {
        $searchtext = $request->search;

        $products = Product::where('title', 'LIKE', "%$searchtext%")->orWhere('title', 'LIKE', "%$searchtext%")->paginate(10);

        return view('home.all_products', compact('products'));
    }
}