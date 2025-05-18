<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        //get data products
        $products = DB::table('products')
            ->when($request->input('name'), function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        //sort by created_at desc

        return view('pages.products.index', compact('products'));
    }

    public function create()
    {
        return view('pages.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|unique:products',
            'cost_price' => 'required|integer',
            'price' => 'required|integer',
            'std_stock' => 'required|integer',
            'stock' => 'required|integer',
            'category' => 'required',
            'image' => 'required|image|mimes:png,jpg,jpeg'
        ]);

        $filename = time() . '.' . $request->image->extension();
        $request->image->storeAs('public/products', $filename);
        $data = $request->all();

        $product = new \App\Models\Product;
        $product->name = $request->name;
        $product->cost_price = (int) $request->cost_price;
        $product->price = (int) $request->price;
        $product->std_stock = (int) $request->std_stock;
        $product->stock = (int) $request->stock;
        $product->category = $request->category;
        $product->image = $filename;
        $product->save();

        return redirect()->route('product.index')->with('success', 'Product successfully created');
    }

    public function edit($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        return view('pages.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        // $data = $request->all();
        // $product = \App\Models\Product::findOrFail($id);
        // $product->update($data);
        $request->validate([
            'name' => 'required|min:3',
            'cost_price' => 'required|integer',
            'price' => 'required|integer',
            'std_stock' => 'required|integer',
            'stock' => 'required|integer',
            'category' => 'required',
            // 'image' => 'required|image|mimes:png,jpg,jpeg'
        ]);

        if ($request->has('image')) {
            $filename = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/products', $filename);
            $data = $request->all();

            // $product = new \App\Models\Product;
            $product = \App\Models\Product::findOrFail($id);
            $product->name = $request->name;
            $product->cost_price = (int) $request->cost_price;
            $product->price = (int) $request->price;
            $product->std_stock = (int) $request->std_stock;
            $product->stock = (int) $request->stock;
            $product->category = $request->category;
            $product->image = $filename;
            $product->update();
        } else {
            $data = $request->all();

            // $product = new \App\Models\Product;
            $product = \App\Models\Product::findOrFail($id);
            $product->name = $request->name;
            $product->cost_price = (int) $request->cost_price;
            $product->price = (int) $request->price;
            $product->std_stock = (int) $request->std_stock;
            $product->stock = (int) $request->stock;
            $product->category = $request->category;
            $product->update();
            }

        return redirect()->route('product.index')->with('success', 'Product successfully updated');
    }

    public function destroy($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->delete();
        return redirect()->route('product.index')->with('success', 'Product successfully deleted');
    }
}
