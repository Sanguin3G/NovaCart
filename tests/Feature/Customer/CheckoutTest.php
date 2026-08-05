<?php

namespace Tests\Feature\Customer;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_rechecks_stock_before_creating_an_order(): void
    {
        $user = User::factory()->create();
        $product = $this->product(stock: 1);

        $response = $this->actingAs($user)
            ->withSession(['cart' => [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => 1,
                    'quantity' => 2,
                ],
            ]])
            ->postJson(route('checkout.process'), $this->checkoutData());

        $response->assertUnprocessable();
        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(1, $product->refresh()->stock);
    }

    public function test_checkout_uses_current_product_price_and_decrements_stock(): void
    {
        Mail::fake();

        $user = User::factory()->create();
        $product = $this->product(stock: 2, price: '12.50');

        $response = $this->actingAs($user)
            ->withSession(['cart' => [
                $product->id => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => 1,
                    'quantity' => 1,
                ],
            ]])
            ->postJson(route('checkout.process'), $this->checkoutData());

        $response->assertOk()->assertJsonPath('order_number', fn ($value) => str_starts_with($value, 'ORD'));
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_amount' => '12.50',
        ]);
        $this->assertDatabaseHas('order_items', [
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => '12.50',
        ]);
        $this->assertSame(1, $product->refresh()->stock);
    }

    private function product(int $stock, string $price = '10.00'): Product
    {
        return Product::create([
            'name' => 'Test product',
            'description' => 'A product used by checkout tests.',
            'price' => $price,
            'stock' => $stock,
            'category_id' => Category::create(['name' => 'Test category'])->id,
            'is_active' => true,
        ]);
    }

    private function checkoutData(): array
    {
        return [
            'shipping_address' => '123 Test Street, Test City',
            'payment_method' => 'credit_card',
        ];
    }
}
