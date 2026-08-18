<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        $admin = Admin::create([
            'name' => 'NovaCart Admin',
            'email' => 'admin@novacart.test',
            'password' => $password,
            'role' => 'admin',
        ]);

        $demo = User::create([
            'name' => 'Demo Customer',
            'email' => 'demo@novacart.test',
            'password' => $password,
        ]);
        $maya = User::create([
            'name' => 'Maya Nguyen',
            'email' => 'maya@novacart.test',
            'password' => $password,
        ]);
        $alex = User::create([
            'name' => 'Alex Carter',
            'email' => 'alex@novacart.test',
            'password' => $password,
        ]);

        foreach ([$admin, $demo, $maya, $alex] as $account) {
            $account->forceFill(['email_verified_at' => now()])->save();
        }

        $categories = collect([
            ['name' => 'Electronics', 'description' => 'Everyday tech for work, play, and staying connected.'],
            ['name' => 'Home & Living', 'description' => 'Useful, good-looking upgrades for your space.'],
            ['name' => 'Fashion', 'description' => 'Comfortable essentials and dependable everyday style.'],
            ['name' => 'Beauty & Wellness', 'description' => 'Simple products for a better daily routine.'],
            ['name' => 'Sports & Outdoors', 'description' => 'Equipment for movement, training, and weekends outside.'],
        ])->mapWithKeys(fn (array $category) => [
            $category['name'] => Category::create($category + ['is_active' => true]),
        ]);

        $products = collect([
            ['name' => 'CloudSound Wireless Headphones', 'category' => 'Electronics', 'price' => 89.90, 'stock' => 24, 'description' => 'Comfortable over-ear headphones with active noise reduction and a thirty-hour battery.', 'image_url' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Aero Mechanical Keyboard', 'category' => 'Electronics', 'price' => 74.50, 'stock' => 16, 'description' => 'A compact mechanical keyboard with hot-swappable switches and warm backlighting.', 'image_url' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Northstar Everyday Laptop', 'category' => 'Electronics', 'price' => 849.00, 'stock' => 7, 'description' => 'A capable, portable laptop for study, work, and creative projects.', 'image_url' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Pulse Smart Watch', 'category' => 'Electronics', 'price' => 129.00, 'stock' => 11, 'description' => 'A lightweight smartwatch with health tracking, notifications, and week-long battery life.', 'image_url' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Beacon Bluetooth Speaker', 'category' => 'Electronics', 'price' => 54.95, 'stock' => 3, 'description' => 'Small enough for a shelf, loud enough for the room, and ready for the outdoors.', 'image_url' => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Lumen Desk Lamp', 'category' => 'Home & Living', 'price' => 42.00, 'stock' => 18, 'description' => 'An adjustable warm-light desk lamp for focused work and late-night reading.', 'image_url' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Oakline Lounge Chair', 'category' => 'Home & Living', 'price' => 219.00, 'stock' => 4, 'description' => 'A simple lounge chair with a solid wood frame and soft woven upholstery.', 'image_url' => 'https://images.unsplash.com/photo-1503602642458-232111445657?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Morning Ritual Coffee Set', 'category' => 'Home & Living', 'price' => 36.75, 'stock' => 32, 'description' => 'A ceramic pour-over set with two matching cups for a slower morning.', 'image_url' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Everyday Cotton Tee', 'category' => 'Fashion', 'price' => 24.00, 'stock' => 45, 'description' => 'A soft, durable cotton T-shirt designed for repeat wear.', 'image_url' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Trail Runner Sneakers', 'category' => 'Fashion', 'price' => 98.00, 'stock' => 9, 'description' => 'Lightweight sneakers with a grippy sole for city miles and weekend trails.', 'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Daytrip Canvas Backpack', 'category' => 'Fashion', 'price' => 69.00, 'stock' => 14, 'description' => 'A roomy canvas backpack with a padded laptop sleeve and easy-access pockets.', 'image_url' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Daily Hydration Serum', 'category' => 'Beauty & Wellness', 'price' => 28.50, 'stock' => 20, 'description' => 'A lightweight daily serum with a clean, comfortable finish.', 'image_url' => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Flex Cork Yoga Mat', 'category' => 'Sports & Outdoors', 'price' => 45.00, 'stock' => 12, 'description' => 'A supportive cork yoga mat with a stable grip for home practice or the studio.', 'image_url' => 'https://images.unsplash.com/photo-1592432678016-e910b452f9a2?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Summit Insulated Bottle', 'category' => 'Sports & Outdoors', 'price' => 31.00, 'stock' => 26, 'description' => 'A reusable insulated bottle that keeps drinks cold through long days outside.', 'image_url' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Studio Instant Camera', 'category' => 'Electronics', 'price' => 119.00, 'stock' => 0, 'description' => 'A cheerful instant camera for capturing physical memories in a digital world.', 'image_url' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Heritage Wool Throw', 'category' => 'Home & Living', 'price' => 84.00, 'stock' => 6, 'description' => 'A warm wool throw with a timeless texture for the sofa or reading chair.', 'image_url' => 'https://images.unsplash.com/photo-1584100936595-c0654b55a2e2?auto=format&fit=crop&w=900&q=80'],
            ['name' => 'Aster Running Cap', 'category' => 'Sports & Outdoors', 'price' => 22.00, 'stock' => 0, 'description' => 'A breathable running cap with a soft brim and adjustable back strap.', 'image_url' => 'https://images.unsplash.com/photo-1521369909029-2afed882baee?auto=format&fit=crop&w=900&q=80', 'is_active' => false],
        ])->mapWithKeys(function (array $product) use ($categories) {
            $key = $product['name'];
            $product['category_id'] = $categories[$product['category']]->id;
            $product['is_active'] = $product['is_active'] ?? true;
            unset($product['category']);

            return [$key => Product::create($product)];
        });

        $reviews = [
            [$products['CloudSound Wireless Headphones'], $maya, 5, 'Comfortable for long work sessions and the battery really does last.'],
            [$products['CloudSound Wireless Headphones'], $alex, 4, 'Good sound and a much better fit than my previous pair.'],
            [$products['Aero Mechanical Keyboard'], $demo, 5, 'Great feel without taking over my desk. The switches are pleasantly quiet.'],
            [$products['Northstar Everyday Laptop'], $maya, 4, 'Fast enough for work and surprisingly light to carry around.'],
            [$products['Lumen Desk Lamp'], $alex, 5, 'The warm setting makes late-night reading much nicer.'],
            [$products['Trail Runner Sneakers'], $demo, 4, 'Comfortable from the first walk and the sole has good grip.'],
            [$products['Daily Hydration Serum'], $maya, 3, 'Nice texture and easy to layer under moisturizer.'],
            [$products['Flex Cork Yoga Mat'], $alex, 5, 'Stable grip and much easier to clean than my old foam mat.'],
        ];

        foreach ($reviews as [$product, $user, $rating, $body]) {
            ProductReview::create([
                'product_id' => $product->id,
                'user_id' => $user->id,
                'reviewer_name' => $user->name,
                'rating' => $rating,
                'body' => $body,
            ]);
        }

        $createOrder = function (User $user, string $number, string $status, string $paymentStatus, string $paymentMethod, array $lines, int $daysAgo) use ($products): Order {
            $total = collect($lines)->sum(fn (array $line) => $products[$line['product']]->price * $line['quantity']);

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $number,
                'total_amount' => $total,
                'status' => $status,
                'shipping_address' => '42 Sample Street, District 1, Ho Chi Minh City',
                'billing_address' => '42 Sample Street, District 1, Ho Chi Minh City',
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'notes' => $status === 'pending' ? 'Please leave the package with reception.' : null,
            ]);
            $order->forceFill([
                'created_at' => now()->subDays($daysAgo),
                'updated_at' => now()->subDays($daysAgo),
            ])->save();

            foreach ($lines as $line) {
                $product = $products[$line['product']];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $line['quantity'],
                    'price' => $product->price,
                ]);
            }

            return $order;
        };

        $createOrder($demo, 'ORD-DEMO-0001', 'pending', 'pending', 'credit_card', [
            ['product' => 'Beacon Bluetooth Speaker', 'quantity' => 1],
            ['product' => 'Morning Ritual Coffee Set', 'quantity' => 2],
        ], 1);
        $createOrder($demo, 'ORD-DEMO-0002', 'completed', 'paid', 'paypal', [
            ['product' => 'Aero Mechanical Keyboard', 'quantity' => 1],
            ['product' => 'Lumen Desk Lamp', 'quantity' => 1],
        ], 16);
        $createOrder($demo, 'ORD-DEMO-0003', 'cancelled', 'refunded', 'bank_transfer', [
            ['product' => 'Northstar Everyday Laptop', 'quantity' => 1],
        ], 27);
        $createOrder($maya, 'ORD-MAYA-0001', 'processing', 'paid', 'credit_card', [
            ['product' => 'CloudSound Wireless Headphones', 'quantity' => 1],
            ['product' => 'Daytrip Canvas Backpack', 'quantity' => 1],
        ], 3);
        $createOrder($maya, 'ORD-MAYA-0002', 'completed', 'paid', 'credit_card', [
            ['product' => 'Trail Runner Sneakers', 'quantity' => 1],
            ['product' => 'Flex Cork Yoga Mat', 'quantity' => 1],
        ], 21);
        $createOrder($alex, 'ORD-ALEX-0001', 'shipped', 'paid', 'bank_transfer', [
            ['product' => 'Pulse Smart Watch', 'quantity' => 1],
            ['product' => 'Summit Insulated Bottle', 'quantity' => 1],
        ], 6);
        $createOrder($alex, 'ORD-ALEX-0002', 'completed', 'paid', 'paypal', [
            ['product' => 'Oakline Lounge Chair', 'quantity' => 1],
        ], 34);
        $createOrder($alex, 'ORD-ALEX-0003', 'pending', 'pending', 'credit_card', [
            ['product' => 'Everyday Cotton Tee', 'quantity' => 2],
            ['product' => 'Daily Hydration Serum', 'quantity' => 1],
        ], 2);
    }
}
