<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        //get data products
        $products = DB::table('stocks')
            ->when($request->input('transaction_time'), function ($query, $transaction_time) {
                return $query->where('transaction_time', 'like', '%' . $transaction_time . '%');
            })
            ->leftJoin('products', 'stocks.product_id', '=', 'products.id')
            ->orderBy('stocks.created_at', 'desc')
            ->paginate(10);
        //sort by created_at desc

        return view('pages.stock.index', compact('products'));
    }

    public function create()
    {
        return view('pages.stock.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $product = \App\Models\Product::where('id',$data['product_id'])->first();
        $data['total_price'] = $product->cost_price * $data['quantity'];
        // @dd($data);
        \App\Models\Stock::create($data);
        $product->stock = $product->stock + $data['quantity'];
        $product->save();

        return redirect()->route('stock.index')->with('success', 'Add Stock successfully created');
    }

    public function index2(Request $request)
    {
        //get data products
        $products = DB::table('stocks')
            ->when($request->input('transaction_time'), function ($query, $transaction_time) {
                return $query->where('transaction_time', 'like', '%' . $transaction_time . '%');
            })
            ->where('type',1)
            ->leftJoin('products', 'stocks.product_id', '=', 'products.id')
            ->orderBy('stocks.created_at', 'desc')
            ->paginate(10);
        //sort by created_at desc

        return view('pages.stock.index2', compact('products'));
    }

    public function create2()
    {
        return view('pages.stock.create2');
    }

    public function store2(Request $request)
    {
        $data = $request->all();
        $product = \App\Models\Product::where('id',$data['product_id'])->first();
        $data['total_price'] = $product->cost_price * $data['quantity'];
        $data['type'] = 1;
        // @dd($data);
        \App\Models\Stock::create($data);
        $product->stock = $product->stock - $data['quantity'];
        $product->save();

        return redirect()->route('stock-opname.index')->with('success', 'Stock Opname successfully created');
    }

    public function edit()
    {
        //
    }

    public function update()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
