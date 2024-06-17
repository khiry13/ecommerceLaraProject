<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;


class AdminController extends Controller
{
    public function view_category()
    {
        if (Auth::id()) {
            $categories = Category::all();
            return view('admin.category', compact('categories'));
        } else {
            return redirect('login');
        }
    }

    public function add_category(Request $request)
    {
        $category = new Category;

        $category->category_name = $request->name;

        $category->save();

        return redirect()->back()->with('message', 'Category Added Successfully...!');
    }

    public function delete_category($id)
    {
        $category = Category::find($id);

        $category->delete();

        return redirect()->back()->with('message', 'Category Deleted Successfullly');
    }

    public function view_product()
    {
        $categories = Category::all();
        return view('admin.product', compact('categories'));
    }

    public function add_product(Request $request)
    {
        $product = new Product;

        $product->title = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount_price = $request->discount;
        $product->quantity = $request->quantity;
        $product->category = $request->category;

        $image = $request->image;
        $image_name = time() . '.' . $image->getClientOriginalExtension();
        $request->image->move('product', $image_name);

        $product->image = $image_name;

        $product->save();

        return redirect()->back()->with('message', 'Product Added Successfully');
    }

    public function show_product()
    {
        $products = Product::all();

        return view('admin.show_product', compact('products'));
    }

    public function delete_product($id)
    {
        $product = Product::find($id);

        $product->delete();

        return redirect()->back()->with('message', 'Product Deleted Successfully');
    }

    public function update_product($id)
    {
        $product = Product::find($id);
        $categories = Category::all();
        return view('admin.update_product', compact('product', 'categories'));
    }

    public function edit_product(Request $request, $id)
    {
        $product = Product::find($id);

        $product->title = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount_price = $request->discount;
        $product->quantity = $request->quantity;
        $product->category = $request->category;

        $image = $request->image;
        if ($image) {
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $request->image->move('product', $image_name);

            $product->image = $image_name;
        }

        $product->save();

        return redirect()->back()->with('message', 'Product Updated Successfully');
    }

    public function order()
    {
        $orders = Order::all();
        return view('admin.order', compact('orders'));
    }

    public function deliverd($id)
    {
        $order = Order::find($id);

        $order->delivery_status = 'Deliverd';

        $order->payment_status = 'Paid';

        $order->save();

        return redirect()->back();
    }

    public function searchdata(Request $request)
    {
        $searchtext = $request->search;

        $orders = Order::where('name', 'LIKE', "%$searchtext%")->orWhere('phone', 'LIKE', "%$searchtext%")->orWhere('product_title', 'LIKE', "%$searchtext%")->get();

        return view('admin.order', compact('orders'));
    }

    public function print_pdf($id)
    {
        $order = Order::find($id);
        $pdf = PDF::loadView('admin.pdf', compact('order'));
        return $pdf->download('order_details.pdf');
    }
}