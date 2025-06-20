<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
// Boot application (important for container bindings)
$app->make(Illuminate\Contracts\Http\Kernel::class);

$controller = $app->make(App\Http\Controllers\Admin\ProductOrderController::class);
$product = App\Models\Product::first();
if (!$product) {
    echo "No product found\n";
    exit(0);
}
$request = Illuminate\Http\Request::create('/', 'GET', [
    'draw' => 1,
    'start' => 0,
    'length' => 10,
]);
$response = $controller->getOrdersForProduct($product, $request);

echo $response->getContent(); 