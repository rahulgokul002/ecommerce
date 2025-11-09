<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $cartItems = CartItem::with('product')->where('user_id', $user->id)->get();

        return view('cart.index', compact('cartItems'));
    }

    public function add($id)
    {
        $user = auth()->user();
        $product = Product::findOrFail($id);

        $cartItem = CartItem::where('user_id', $user->id)
                            ->where('product_id', $product->id)
                            ->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 1
            ]);
        }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::where('id', $id)
                            ->where('user_id', $user->id)
                            ->first();

        if ($cartItem) {
            if ($request->quantity > $cartItem->product->quantity) {
                return redirect()->back()->with('error', 'Quantity exceeds available stock.');
            }

            $cartItem->quantity = $request->quantity;
            $cartItem->save();

            return redirect()->back()->with('success', 'Cart updated successfully!');
        }

        return redirect()->back()->with('error', 'Cart item not found.');
    }

    public function remove($id)
    {
        $user = auth()->user();

        $cartItem = CartItem::where('id', $id)
                            ->where('user_id', $user->id)
                            ->first();

        if ($cartItem) {
            $cartItem->delete();
            return redirect()->back()->with('success', 'Product removed from cart!');
        }

        return redirect()->back()->with('error', 'Cart item not found.');

    }
    public function apiAdd(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = auth()->user();
        $product = Product::findOrFail($request->product_id);

        $cartItem = CartItem::where('user_id', $user->id)
                            ->where('product_id', $product->id)
                            ->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'quantity' => 1
            ]);
        }

        return response()->json([
            'message' => 'Product added to cart successfully!',
            'cart_item' => $cartItem
        ], 200);
    }
    public function apiRemove($id)
    {
        $user = auth()->user();

        $cartItem = CartItem::where('id', $id)
                            ->where('user_id', $user->id)
                            ->first();

        if ($cartItem) {
            $cartItem->delete();

            return response()->json([
                'message' => 'Product removed from cart successfully.'
            ], 200);
        }

        return response()->json([
            'error' => 'Cart item not found.'
        ], 404);
    }

}
