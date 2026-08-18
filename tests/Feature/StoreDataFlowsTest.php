<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StoreDataFlowsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_and_customer_data_fragments_render_without_server_errors(): void
    {
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'store-admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $customer = User::factory()->create();
        $category = Category::create(['name' => 'Test category', 'description' => 'Test', 'is_active' => true]);
        $product = Product::create([
            'name' => 'Test product',
            'description' => 'Test product description',
            'price' => 25,
            'stock' => 10,
            'category_id' => $category->id,
            'image_url' => 'https://example.com/product.jpg',
            'is_active' => true,
        ]);
        $order = Order::create([
            'user_id' => $customer->id,
            'order_number' => 'ORD-TEST-0001',
            'total_amount' => 25,
            'status' => 'completed',
            'shipping_address' => 'Test address',
            'billing_address' => 'Test address',
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
        ]);
        OrderItem::create(['order_id' => $order->id, 'product_id' => $product->id, 'quantity' => 1, 'price' => 25]);

        $this->actingAs($admin, 'admin');
        $this->get(route('admin.products.data'))->assertOk()->assertSee('Test product');
        $this->get(route('admin.orders.data'))->assertOk()->assertSee('ORD-TEST-0001');
        $this->get(route('admin.reviews.data'))->assertOk();
        $this->get(route('admin.users.data'))->assertOk()->assertSee($customer->email);

        $this->actingAs($customer, 'web');
        $this->get(route('orders.data'))->assertOk()->assertSee('ORD-TEST-0001');
        $this->get(route('reviews.pending'))->assertOk()->assertSee('Test product');
        $this->get(route('reviews.mine'))->assertOk();
    }

    public function test_admin_can_moderate_reviews_and_manage_customer_accounts(): void
    {
        $admin = Admin::create([
            'name' => 'Test Admin',
            'email' => 'moderator@example.com',
            'password' => Hash::make('password'),
        ]);
        $customer = User::factory()->create(['is_active' => true]);
        $category = Category::create(['name' => 'Test category', 'description' => 'Test', 'is_active' => true]);
        $product = Product::create(['name' => 'Reviewed product', 'description' => 'Test', 'price' => 20, 'stock' => 5, 'category_id' => $category->id, 'image_url' => 'https://example.com/review.jpg', 'is_active' => true]);
        $review = ProductReview::create(['product_id' => $product->id, 'user_id' => $customer->id, 'reviewer_name' => $customer->name, 'rating' => 5, 'body' => 'Excellent']);

        $this->actingAs($admin, 'admin');
        $this->get(route('admin.reviews.show', $review))->assertOk()->assertSee('Excellent');
        $this->patch(route('admin.reviews.disable', $review))->assertOk();
        $this->assertSoftDeleted('product_reviews', ['id' => $review->id]);
        $this->patch(route('admin.reviews.disable', $review))->assertOk();
        $this->assertDatabaseHas('product_reviews', ['id' => $review->id, 'deleted_at' => null]);

        $this->patch(route('admin.users.toggleStatus', $customer))->assertOk();
        $this->assertDatabaseHas('users', ['id' => $customer->id, 'is_active' => false]);
        $this->put(route('admin.users.update', $customer), ['name' => 'Updated Customer', 'email' => 'updated@example.com'])->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => $customer->id, 'name' => 'Updated Customer', 'email' => 'updated@example.com']);
    }
}
