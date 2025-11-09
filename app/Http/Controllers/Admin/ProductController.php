<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function dashboard(Request $request)
    {
        $query = Product::query();

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('availability')) {
            if ($request->availability === 'in') {
                $query->where('quantity', '>', 0);
            } elseif ($request->availability === 'out') {
                $query->where('quantity', '=', 0);
            }
        }

        $products = $query->get();

        if (Auth::user()->role === 'admin') {
            return view('admin.dashboard', compact('products'));
        } else {
            return view('dashboard', compact('products'));
        }
    }


    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        $products = Product::all();
        return view('admin.products.index', compact('products'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0.01',
            'quantity' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        \App\Models\Product::create($validated);

        return redirect()->route('admin.products.index')
                        ->with('success', 'Product added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::find($id);
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
   public function destroy(string $id)
    {
        $product = Product::findOrFail($id);

        if ($product->image && file_exists(storage_path('app/public/' . $product->image))) {
            unlink(storage_path('app/public/' . $product->image));
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
    public function report()
    {
        $totalOrders = Order::count();

        $totalRevenue = Order::sum('total_amount');

        $revenueByDate = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as revenue'))
                            ->groupBy('date')
                            ->orderBy('date', 'asc')
                            ->get();

        return view('admin.report', compact('totalOrders', 'totalRevenue', 'revenueByDate'));
    }
    public function apiIndex(Request $request)
    {
        $query = Product::query();

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('availability')) {
            if ($request->availability === 'in') {
                $query->where('quantity', '>', 0);
            } elseif ($request->availability === 'out') {
                $query->where('quantity', '=', 0);
            }
        }

        $products = $query->get(['id', 'name', 'price', 'quantity', 'description', 'image']);

        return response()->json([
            'success' => true,
            'count' => $products->count(),
            'data' => $products
        ], 200);
    }


}
