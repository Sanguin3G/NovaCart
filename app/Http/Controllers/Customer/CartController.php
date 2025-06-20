<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Cart\StoreCartItemRequest;
use App\Http\Requests\Customer\Cart\UpdateCartItemRequest;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Get the total number of items in the cart.
     */
    private function getCartItemCount(): int
    {
        return count(Session::get('cart', []));
    }

    /**
     * Display the current cart contents.
     */
    public function view(Request $request): View|JsonResponse
    {
        $cart = Session::get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        if ($request->ajax()) {
            return response()->json([
                'cart' => $cart,
                'total' => $total,
                'cartItemCount' => $this->getCartItemCount(),
            ]);
        }

        return view('customer.cart.index', compact('cart', 'total'));
    }

    /**
     * Add an item to the cart.
     */
    public function add(StoreCartItemRequest $request, Product $product): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $quantity = $validated['quantity'];

        $cart = Session::get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity,
            ];
        }

        Session::put('cart', $cart);

        if ($request->ajax()) {
            return response()->json([
                'message' => "'$product->name' added to your cart!",
                'cartItemCount' => $this->getCartItemCount(),
            ]);
        }

        return back()->with('success', "'$product->name' added to your cart!");
    }

    /**
     * Update the quantity of an item in the cart.
     */
    public function update(UpdateCartItemRequest $request, $productId): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $quantity = $validated['quantity'];

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $product = Product::find($productId);
            if ($product && $product->stock_quantity < $quantity) {
                if ($request->ajax()) {
                    return response()->json(['error' => 'Not enough stock available.'], 422);
                }
                return back()->with('error', 'Not enough stock available for the new quantity.');
            }

            $cart[$productId]['quantity'] = $quantity;
            Session::put('cart', $cart);

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Cart updated successfully.',
                    'cartItemCount' => $this->getCartItemCount(),
                ]);
            }
            return redirect()->route('cart.view')->with('success', 'Cart updated successfully.');
        }

        if ($request->ajax()) {
            return response()->json(['error' => 'Item not found in cart.'], 404);
        }
        return redirect()->route('cart.view')->with('error', 'Item not found in cart.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request, $productId): RedirectResponse|JsonResponse
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Item removed from cart.',
                    'cartItemCount' => $this->getCartItemCount(),
                ]);
            }
            return redirect()->route('cart.view')->with('success', 'Item removed from cart.');
        }
        
        if ($request->ajax()) {
            return response()->json(['error' => 'Item not found in cart.'], 404);
        }
        return redirect()->route('cart.view')->with('error', 'Item not found in cart.');
    }
}
