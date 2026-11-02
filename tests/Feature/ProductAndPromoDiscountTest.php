<?php

namespace Tests\Feature;

use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductAndPromoDiscountTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::create([
            'name' => 'Admin User',
            'email' => 'admin@notisse.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);
    }

    private function createCustomer(): User
    {
        return User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);
    }

    /** Test 1: Admin can store product with discount_price */
    public function test_admin_can_set_and_update_discount_price_on_product(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $response = $this->post('/admin/products', [
            'name' => 'Oversized Vintage Tee',
            'price' => 300000,
            'discount_price' => 240000,
            'description' => 'Heavyweight cotton tee',
        ]);

        $response->assertRedirect();
        $product = Product::where('name', 'Oversized Vintage Tee')->first();
        $this->assertNotNull($product);
        $this->assertEquals(300000, $product->price);
        $this->assertEquals(240000, $product->discount_price);
        $this->assertTrue($product->hasDiscount());
        $this->assertTrue($product->is_discounted);
        $this->assertEquals(240000, $product->effective_price);

        // Update product discount
        $updateRes = $this->put('/admin/products/'.$product->id, [
            'name' => 'Oversized Vintage Tee',
            'price' => 300000,
            'discount_price' => 200000,
            'description' => 'Updated description',
        ]);

        $updateRes->assertRedirect();
        $product->refresh();
        $this->assertEquals(200000, $product->discount_price);
        $this->assertEquals(200000, $product->effective_price);
    }

    /** Test 2: Admin cannot set discount price higher than or equal to normal price */
    public function test_admin_cannot_set_discount_price_higher_than_or_equal_to_price(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $response = $this->post('/admin/products', [
            'name' => 'Invalid Discount Tee',
            'price' => 250000,
            'discount_price' => 260000,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('products', ['name' => 'Invalid Discount Tee']);
    }

    /** Test 3: Admin can create promo code restricted to specific products */
    public function test_admin_can_create_discount_code_for_specific_products(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $p1 = Product::create(['name' => 'Apparel A', 'price' => 200000]);
        $p2 = Product::create(['name' => 'Apparel B', 'price' => 300000]);

        $response = $this->post('/admin/discounts', [
            'code' => 'APPARELONLY',
            'type' => 'percentage',
            'value' => 20,
            'applies_to' => 'specific_products',
            'product_ids' => [$p1->id],
        ]);

        $response->assertRedirect('/admin#section-discounts');
        $discount = DiscountCode::where('code', 'APPARELONLY')->first();
        $this->assertNotNull($discount);
        $this->assertEquals('specific_products', $discount->applies_to);
        $this->assertCount(1, $discount->products);
        $this->assertEquals($p1->id, $discount->products->first()->id);
    }

    /** Test 4: Discount code restricted to specific products fails if cart does not contain that product */
    public function test_specific_product_discount_fails_when_product_not_in_cart(): void
    {
        $p1 = Product::create(['name' => 'Eligible Product', 'price' => 200000]);
        $p2 = Product::create(['name' => 'Ineligible Product', 'price' => 300000]);

        $discount = DiscountCode::create([
            'code' => 'TARGETED10',
            'type' => 'percentage',
            'value' => 10,
            'applies_to' => 'specific_products',
            'is_active' => true,
        ]);
        $discount->products()->sync([$p1->id]);

        // Cart with only p2 (ineligible)
        $res = $this->postJson('/api/check-promo', [
            'code' => 'TARGETED10',
            'subtotal' => 300000,
            'items' => [
                ['id' => $p2->id, 'price' => 300000, 'quantity' => 1],
            ],
        ]);

        $res->assertStatus(422);
        $res->assertJson([
            'success' => false,
            'message' => 'Kode diskon ini hanya berlaku untuk produk tertentu yang tidak ada di keranjang Anda.',
        ]);
    }

    /** Test 5: Discount code cannot be used on already discounted products */
    public function test_discount_code_rejected_when_product_is_already_discounted(): void
    {
        // Product A is already discounted from 300.000 to 250.000
        $discountedProduct = Product::create([
            'name' => 'Discounted Jacket',
            'price' => 300000,
            'discount_price' => 250000,
        ]);

        $discount = DiscountCode::create([
            'code' => 'GENERAL10',
            'type' => 'percentage',
            'value' => 10,
            'applies_to' => 'all',
            'is_active' => true,
        ]);

        // Check promo with only already discounted product
        $res = $this->postJson('/api/check-promo', [
            'code' => 'GENERAL10',
            'subtotal' => 250000,
            'items' => [
                ['id' => $discountedProduct->id, 'price' => 250000, 'quantity' => 1],
            ],
        ]);

        $res->assertStatus(422);
        $res->assertJson([
            'success' => false,
            'message' => 'Kode diskon/potongan harga tidak dapat digunakan pada produk yang sudah diskon.',
        ]);
    }

    /** Test 6: Mixed cart applies promo discount only to non-discounted eligible items */
    public function test_mixed_cart_calculates_discount_only_on_eligible_non_discounted_items(): void
    {
        $discountedProduct = Product::create([
            'name' => 'Discounted Jacket',
            'price' => 300000,
            'discount_price' => 200000, // active price 200.000
        ]);

        $regularProduct = Product::create([
            'name' => 'Regular Cap',
            'price' => 100000,
            'discount_price' => null, // normal price 100.000
        ]);

        $discount = DiscountCode::create([
            'code' => 'SAVE20',
            'type' => 'percentage',
            'value' => 20, // 20%
            'applies_to' => 'all',
            'is_active' => true,
        ]);

        $res = $this->postJson('/api/check-promo', [
            'code' => 'SAVE20',
            'subtotal' => 300000, // 200.000 + 100.000
            'items' => [
                ['id' => $discountedProduct->id, 'price' => 200000, 'quantity' => 1],
                ['id' => $regularProduct->id, 'price' => 100000, 'quantity' => 1],
            ],
        ]);

        $res->assertOk();
        $res->assertJson([
            'success' => true,
            'code' => 'SAVE20',
            // 20% of 100.000 (regular product only) = 20.000 (NOT 20% of 300.000 = 60.000)
            'discount_amount' => 20000,
        ]);
    }

    /** Test 7: Checkout correctly charges effective price and enforces promo exclusion */
    public function test_checkout_charges_effective_price_and_enforces_discount_exclusion(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);

        $discountedProduct = Product::create([
            'name' => 'Discounted Jacket',
            'price' => 300000,
            'discount_price' => 200000,
        ]);
        ProductStock::create([
            'product_id' => $discountedProduct->id,
            'size' => 'L',
            'stock' => 10,
        ]);

        $regularProduct = Product::create([
            'name' => 'Regular Cap',
            'price' => 100000,
        ]);
        ProductStock::create([
            'product_id' => $regularProduct->id,
            'size' => 'All Size',
            'stock' => 10,
        ]);

        $promo = DiscountCode::create([
            'code' => 'TEST10',
            'type' => 'percentage',
            'value' => 10,
            'applies_to' => 'all',
            'is_active' => true,
        ]);

        $res = $this->postJson('/checkout', [
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Merdeka No 1',
            'destination_area_id' => 'IDNP6IDNC148IDND2093',
            'shipping_cost' => 15000,
            'courier_name' => 'JNE Reguler',
            'promo_code' => 'TEST10',
            'items' => [
                ['id' => $discountedProduct->id, 'name' => 'Discounted Jacket', 'price' => 200000, 'quantity' => 1, 'size' => 'L'],
                ['id' => $regularProduct->id, 'name' => 'Regular Cap', 'price' => 100000, 'quantity' => 1, 'size' => 'All Size'],
            ],
        ]);

        $res->assertOk();
        $order = Order::latest()->first();
        $this->assertNotNull($order);
        // Subtotal = 200.000 + 100.000 = 300.000
        $this->assertEquals(300000, $order->subtotal);
        // Promo only on regular product: 10% of 100.000 = 10.000
        $this->assertEquals(10000, $order->discount_amount);
        // Total = 300.000 - 10.000 + 15.000 (shipping) = 305.000
        $this->assertEquals(305000, $order->total_amount);
    }

    /** Test 8: Cart API formats items with effective discounted price */
    public function test_cart_api_returns_discount_pricing(): void
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer);

        $discountedProduct = Product::create([
            'name' => 'Discounted Hood',
            'price' => 500000,
            'discount_price' => 350000,
        ]);

        $res = $this->postJson('/api/cart', [
            'product_id' => $discountedProduct->id,
            'size' => 'M',
            'quantity' => 1,
        ]);

        $res->assertOk();
        $res->assertJsonFragment([
            'id' => $discountedProduct->id,
            'price' => 350000,
            'unitPrice' => 350000,
            'original_price' => 500000,
            'is_discounted' => true,
        ]);
    }

    /** Test 9: Fixed discount code on specific product */
    public function test_specific_product_with_fixed_discount_amount(): void
    {
        $p1 = Product::create(['name' => 'Eligible Shirt', 'price' => 250000]);
        $p2 = Product::create(['name' => 'Ineligible Shoes', 'price' => 400000]);

        $promo = DiscountCode::create([
            'code' => 'POTONG50K',
            'type' => 'fixed',
            'value' => 50000, // Rp 50.000 off
            'applies_to' => 'specific_products',
            'is_active' => true,
        ]);
        $promo->products()->sync([$p1->id]);

        $res = $this->postJson('/api/check-promo', [
            'code' => 'POTONG50K',
            'subtotal' => 650000,
            'items' => [
                ['id' => $p1->id, 'price' => 250000, 'quantity' => 1],
                ['id' => $p2->id, 'price' => 400000, 'quantity' => 1],
            ],
        ]);

        $res->assertOk();
        $res->assertJson([
            'success' => true,
            'code' => 'POTONG50K',
            'discount_amount' => 50000,
        ]);
    }

    /** Test 10: Admin can store and update model specifications (gender, height, weight, size) */
    public function test_admin_can_store_and_update_model_specifications_on_product(): void
    {
        $admin = $this->createAdmin();
        $this->actingAs($admin);

        $response = $this->post('/admin/products', [
            'name' => 'Signature Oversized Graphic Tee',
            'price' => 320000,
            'model_gender' => 'Male',
            'model_height' => 180,
            'model_weight' => 70,
            'model_size' => 'XL',
            'description' => 'Cotton Combed 16s',
        ]);

        $response->assertRedirect();
        $product = Product::where('name', 'Signature Oversized Graphic Tee')->first();
        $this->assertNotNull($product);
        $this->assertEquals('Male', $product->model_gender);
        $this->assertEquals(180, $product->model_height);
        $this->assertEquals(70, $product->model_weight);
        $this->assertEquals('XL', $product->model_size);

        // Update model specifications
        $updateRes = $this->put('/admin/products/'.$product->id, [
            'name' => 'Signature Oversized Graphic Tee',
            'price' => 320000,
            'model_gender' => 'Female',
            'model_height' => 168,
            'model_weight' => 52,
            'model_size' => 'M',
        ]);

        $updateRes->assertRedirect();
        $product->refresh();
        $this->assertEquals('Female', $product->model_gender);
        $this->assertEquals(168, $product->model_height);
        $this->assertEquals(52, $product->model_weight);
        $this->assertEquals('M', $product->model_size);
    }

    /** Test 11: Storefront detail page renders product model specifications */
    public function test_storefront_displays_product_model_specifications(): void
    {
        $product = Product::create([
            'name' => 'Atelier Fitted Tee',
            'price' => 280000,
            'model_gender' => 'Female',
            'model_height' => 170,
            'model_weight' => 54,
            'model_size' => 'S',
        ]);

        $response = $this->get('/product/'.$product->id);
        $response->assertOk();
        $response->assertSee('Female · Height 170 cm / Weight 54 kg — wearing size');
        $response->assertSee('S');
    }
}
