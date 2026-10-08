<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private function getCart(Request $request)
    {
        $userId = auth()->id();
        $sessionId = $request->cookie('notisse_cart_session');

        if (!$userId && !$sessionId) {
            $sessionId = Str::uuid()->toString();
        }

        if ($userId) {
            $cart = Cart::firstOrCreate(['user_id' => $userId]);
        } else {
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        return [$cart, $sessionId];
    }

    private function getFormattedItems($cart)
    {
        return $cart->items()->with('product')->get()->map(function($item) {
            $imageUrl = asset('cart-placeholder.jpg'); // fallback
            if ($item->product && $item->product->image) {
                $imageUrl = asset($item->product->image);
            }

            return [
                'cart_item_id' => $item->id,
                'id' => $item->product_id,
                'name' => $item->product ? $item->product->name : 'Unknown Product',
                'price' => $item->product ? $item->product->price : 0,
                'unitPrice' => $item->product ? $item->product->price : 0, // needed for existing JS
                'qty' => $item->quantity,
                'size' => $item->size,
                'image' => $imageUrl
            ];
        });
    }

    public function index(Request $request)
    {
        list($cart, $sessionId) = $this->getCart($request);
        $items = $this->getFormattedItems($cart);
        
        $response = response()->json($items);
        if ($sessionId && !$request->cookie('notisse_cart_session')) {
            $response->cookie('notisse_cart_session', $sessionId, 60 * 24 * 30);
        }
        return $response;
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1'
        ]);

        list($cart, $sessionId) = $this->getCart($request);

        $item = $cart->items()->where('product_id', $validated['product_id'])
            ->where('size', $validated['size'])->first();

        if ($item) {
            $item->increment('quantity', $validated['quantity']);
        } else {
            $cart->items()->create($validated);
        }

        $items = $this->getFormattedItems($cart);
        $response = response()->json(['success' => true, 'items' => $items]);
        
        if ($sessionId && !$request->cookie('notisse_cart_session')) {
            $response->cookie('notisse_cart_session', $sessionId, 60 * 24 * 30);
        }
        return $response;
    }

    public function updateItem(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        list($cart, $sessionId) = $this->getCart($request);
        $item = $cart->items()->find($id);
        
        if ($item) {
            $item->update(['quantity' => $validated['quantity']]);
        }

        return response()->json(['success' => true, 'items' => $this->getFormattedItems($cart)]);
    }

    public function remove(Request $request, $id)
    {
        list($cart, $sessionId) = $this->getCart($request);
        $item = $cart->items()->find($id);
        
        if ($item) {
            $item->delete();
        }

        return response()->json(['success' => true, 'items' => $this->getFormattedItems($cart)]);
    }

    public function clear(Request $request)
    {
        list($cart, $sessionId) = $this->getCart($request);
        $cart->items()->delete();
        return response()->json(['success' => true, 'items' => []]);
    }
}
