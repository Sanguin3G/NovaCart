<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Checkout\ProcessCheckoutRequest;
use App\Mail\OrderProcessedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use RuntimeException;

class CheckoutController extends Controller
{
    /**
     * Show the checkout form if cart is not empty.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.view')
                ->with('error', 'Your cart is empty. Please add items before proceeding to checkout.');
        }

        $user = $request->user();

        return view('customer.checkout.index', compact('user', 'cart'));
    }

    /**
     * Process the checkout and create an order.
     */
    public function process(ProcessCheckoutRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your cart is empty.'], 400);
            }
            return redirect()->route('cart.view')->with('error', 'Your cart became empty during checkout. Please try again.');
        }

        try {
            $order = DB::transaction(function () use ($cart, $validated, $request) {
                $products = Product::query()
                    ->whereIn('id', array_keys($cart))
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                if ($products->count() !== count($cart)) {
                    throw new RuntimeException('One or more products are no longer available.');
                }

                $totalAmount = 0;
                foreach ($cart as $productId => $details) {
                    $quantity = (int) ($details['quantity'] ?? 0);
                    $product = $products->get($productId);

                    if ($quantity < 1 || $product->stock < $quantity) {
                        throw new RuntimeException("Not enough stock available for {$product->name}.");
                    }

                    $totalAmount += (float) $product->price * $quantity;
                }

                $order = Order::create([
                    'user_id' => $request->user()->id,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'shipping_address' => $validated['shipping_address'],
                    'billing_address' => $validated['billing_address'] ?? $validated['shipping_address'],
                    'payment_method' => $validated['payment_method'],
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($cart as $productId => $details) {
                    $quantity = (int) $details['quantity'];
                    $product = $products->get($productId);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $product->price,
                    ]);

                    $product->decrement('stock', $quantity);
                }

                return $order;
            });

            // Always send confirmation email if we have an email address
            $userEmail = $request->user()->email;
            if ($userEmail) {
                $userName = $request->user()->name;

                $order->load('user', 'orderItems.product');
                try {
                    Mail::to($userEmail)->send(new OrderProcessedMail($order, $userName, $userEmail));
                    Log::info("Order processed email sent to {$userEmail} for order {$order->id}.");
                } catch (Exception $e) {
                    Log::error("Failed to send order processed email for order {$order->id}: " . $e->getMessage());
                }
            }

            Session::forget('cart');

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Order placed successfully!',
                    'order_number' => $order->order_number,
                    'redirect_url' => route('orders.show', $order)
                ]);
            }

            return redirect()->route('orders.show', $order)->with('success', 'Your order #' . $order->order_number . ' has been placed successfully!');

        } catch (RuntimeException $e) {
            Log::warning('Checkout validation failed: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }

            return back()->with('error', $e->getMessage())->withInput();
        } catch (Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json(['message' => 'There was an error processing your order. Please try again.'], 500);
            }
            
            return back()->with('error', 'There was an error processing your order. Please try again.')->withInput();
        }
    }
}
