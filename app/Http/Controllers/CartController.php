<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public static function mergeGuestCart(int $userId, ?string $sessionId): void
    {
        if (! $sessionId) {
            return;
        }

        $guestCart = Cart::where('session_id', $sessionId)->first();
        if (! $guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $userId]);

        foreach ($guestCart->items as $guestItem) {
            $existing = $userCart->items()
                ->where('product_id', $guestItem->product_id)
                ->where('size', $guestItem->size)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $guestItem->quantity);
                $guestItem->delete();
            } else {
                $guestItem->update(['cart_id' => $userCart->id]);
            }
        }

        $guestCart->delete();
    }

    private function getCart(Request $request)
    {
        $userId = auth()->id();
        $sessionId = $request->cookie('notisse_cart_session');

        if ($userId) {
            if ($sessionId) {
                self::mergeGuestCart($userId, $sessionId);
            }
            $cart = Cart::firstOrCreate(['user_id' => $userId]);
        } else {
            if (! $sessionId) {
                $sessionId = Str::uuid()->toString();
            }
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }

        return [$cart, $sessionId];
    }

    private function getFormattedItems($cart)
    {
        return $cart->items()->with('product')->get()->map(function ($item) {
            $imageUrl = asset('footage-baju.jpg'); // fallback
            if ($item->product && $item->product->image) {
                $img = $item->product->image;
                $imageUrl = Str::startsWith($img, ['http://', 'https://']) ? $img : asset($img);
            }

            return [
                'cart_item_id' => $item->id,
                'id' => $item->product_id,
                'name' => $item->product ? $item->product->name : 'Unknown Product',
                'price' => $item->product ? $item->product->price : 0,
                'unitPrice' => $item->product ? $item->product->price : 0, // needed for existing JS
                'qty' => $item->quantity,
                'size' => $item->size,
                'image' => $imageUrl,
            ];
        });
    }

    public function index(Request $request)
    {
        [$cart, $sessionId] = $this->getCart($request);
        $items = $this->getFormattedItems($cart);

        $response = response()->json($items);
        if ($sessionId && ! $request->cookie('notisse_cart_session')) {
            $response->cookie('notisse_cart_session', $sessionId, 60 * 24 * 30);
        }

        return $response;
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        [$cart, $sessionId] = $this->getCart($request);

        $item = $cart->items()->where('product_id', $validated['product_id'])
            ->where('size', $validated['size'])->first();

        if ($item) {
            $item->increment('quantity', $validated['quantity']);
        } else {
            $cart->items()->create($validated);
        }

        $items = $this->getFormattedItems($cart);
        $response = response()->json(['success' => true, 'items' => $items]);

        if ($sessionId && ! $request->cookie('notisse_cart_session')) {
            $response->cookie('notisse_cart_session', $sessionId, 60 * 24 * 30);
        }

        return $response;
    }

    public function updateItem(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        [$cart, $sessionId] = $this->getCart($request);
        $item = $cart->items()->find($id);

        if ($item) {
            $item->update(['quantity' => $validated['quantity']]);
        }

        return response()->json(['success' => true, 'items' => $this->getFormattedItems($cart)]);
    }

    public function remove(Request $request, $id)
    {
        [$cart, $sessionId] = $this->getCart($request);
        $item = $cart->items()->find($id);

        if ($item) {
            $item->delete();
        }

        return response()->json(['success' => true, 'items' => $this->getFormattedItems($cart)]);
    }

    public function clear(Request $request)
    {
        [$cart, $sessionId] = $this->getCart($request);
        $cart->items()->delete();

        return response()->json(['success' => true, 'items' => []]);
    }
}
