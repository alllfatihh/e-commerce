<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\Artist;
use App\Models\Artwork;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Hash;

class NotisseE2ETest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** 1. Storefront Public Navigation */
    public function test_public_pages_load_successfully(): void
    {
        $this->get('/')->assertStatus(200);
        $this->get('/shop')->assertStatus(200);
        $this->get('/about')->assertStatus(200);
        $this->get('/contact')->assertStatus(200);
        $this->get('/artist')->assertStatus(200);
        $this->get('/editorial')->assertStatus(200);
        $this->get('/lookbook')->assertStatus(200);
        $this->get('/login')->assertStatus(200);
        $this->get('/register')->assertStatus(200);
    }

    /** 2. Authentication Flow */
    public function test_user_registration_and_validation(): void
    {
        // Missing fields
        $res = $this->postJson('/register', []);
        $res->assertStatus(422);

        // Valid registration
        $res = $this->postJson('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);
        $res->assertStatus(200)
            ->assertJson([
                'success' => true,
                'redirect' => '/account',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
            'name' => 'John Doe',
        ]);

        // Duplicate registration should fail
        $resDup = $this->postJson('/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ]);
        $resDup->assertStatus(422);
    }

    public function test_login_validation_and_role_redirect(): void
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('mypassword'),
            'role' => 'user',
        ]);

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('adminpassword'),
            'role' => 'admin',
        ]);

        // Invalid credentials
        $resInvalid = $this->postJson('/login', [
            'email' => 'user@example.com',
            'password' => 'wrongpassword',
        ]);
        $resInvalid->assertStatus(422);

        // Non-existent user
        $resNotFound = $this->postJson('/login', [
            'email' => 'nobody@example.com',
            'password' => 'wrongpassword',
        ]);
        $resNotFound->assertStatus(422);

        // Valid customer login -> redirects to /account
        $resUser = $this->postJson('/login', [
            'email' => 'user@example.com',
            'password' => 'mypassword',
        ]);
        $resUser->assertStatus(200)
            ->assertJson([
                'success' => true,
                'redirect' => '/account',
            ]);

        // Valid admin login -> redirects to /admin
        $resAdmin = $this->postJson('/login', [
            'email' => 'admin@example.com',
            'password' => 'adminpassword',
        ]);
        $resAdmin->assertStatus(200)
            ->assertJson([
                'success' => true,
                'redirect' => '/admin',
            ]);
    }

    public function test_admin_route_protection(): void
    {
        // Unauthenticated access
        $this->get('/admin')->assertRedirect('/login');

        // Non-admin user access
        $user = User::create([
            'name' => 'Regular Customer',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
        $this->actingAs($user)->get('/admin')->assertStatus(403);

        // Admin access
        $admin = User::create([
            'name' => 'Master Admin',
            'email' => 'master@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        $this->actingAs($admin)->get('/admin')->assertStatus(200);
    }

    /** 3. Account Protection */
    public function test_account_page_requires_authentication(): void
    {
        $this->get('/account')->assertRedirect('/login');

        $user = User::create([
            'name' => 'Authenticated Person',
            'email' => 'auth@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($user)->get('/account')->assertStatus(200);
    }

    /** 4. User Address Book */
    public function test_user_address_book_management(): void
    {
        $user = User::create([
            'name' => 'Address User',
            'email' => 'address@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($user);

        // Create first address
        $res = $this->postJson('/api/user/addresses', [
            'label' => 'Home',
            'recipient_name' => 'John Doe',
            'phone' => '081234567890',
            'area_id' => 'area_123',
            'area_name' => 'Bandung, Jawa Barat',
            'address' => 'Jl. Dago No. 10',
            'is_primary' => true,
        ]);
        $res->assertStatus(200);
        $addr1 = UserAddress::where('user_id', $user->id)->first();
        $this->assertNotNull($addr1);
        $this->assertTrue((bool)$addr1->is_primary);

        // Create second address as primary
        $res2 = $this->postJson('/api/user/addresses', [
            'label' => 'Office',
            'recipient_name' => 'John Work',
            'phone' => '081234567891',
            'area_id' => 'area_456',
            'area_name' => 'Jakarta Selatan, DKI Jakarta',
            'address' => 'Jl. Sudirman No. 1',
            'is_primary' => true,
        ]);
        $res2->assertStatus(200);

        // Address 1 should now not be primary, Address 2 should be primary
        $this->assertFalse((bool)$addr1->fresh()->is_primary);
        $addr2 = UserAddress::where('label', 'Office')->first();
        $this->assertTrue((bool)$addr2->is_primary);

        // Update address 1
        $resUpdate = $this->putJson("/api/user/addresses/{$addr1->id}", [
            'label' => 'Home Updated',
            'recipient_name' => 'John Doe Updated',
            'phone' => '081234567890',
            'area_id' => 'area_123',
            'area_name' => 'Bandung, Jawa Barat',
            'address' => 'Jl. Dago No. 20',
        ]);
        $resUpdate->assertStatus(200);
        $this->assertEquals('Home Updated', $addr1->fresh()->label);

        // Delete address
        $resDel = $this->deleteJson("/api/user/addresses/{$addr1->id}");
        $resDel->assertStatus(200);
        $this->assertNull(UserAddress::find($addr1->id));
    }

    /** 5. Cart Operations */
    public function test_cart_api_operations(): void
    {
        $user = User::create([
            'name' => 'Cart User',
            'email' => 'cart@example.com',
            'password' => Hash::make('password'),
        ]);

        $product = Product::create([
            'name' => 'NOTISSE LUXURY TEE',
            'price' => 350000,
            'description' => 'Test shirt',
        ]);

        ProductStock::create([
            'product_id' => $product->id,
            'size' => 'L',
            'stock' => 10,
        ]);

        $this->actingAs($user);

        // 1. Add to cart
        $resAdd = $this->postJson('/api/cart', [
            'product_id' => $product->id,
            'size' => 'L',
            'quantity' => 2,
        ]);
        $resAdd->assertStatus(200);

        // 2. View cart
        $resGet = $this->getJson('/api/cart');
        $resGet->assertStatus(200);
        $cart = $resGet->json();
        $this->assertNotEmpty($cart);

        // 3. Clear cart
        $resClear = $this->postJson('/api/cart/clear');
        $resClear->assertStatus(200);

        $resGetEmpty = $this->getJson('/api/cart');
        $this->assertEmpty($resGetEmpty->json());
    }

    /** 6. Artwork Offering */
    public function test_artwork_offering_submission(): void
    {
        $user = User::create([
            'name' => 'Offering User',
            'email' => 'offering@example.com',
            'password' => Hash::make('password'),
        ]);

        $artist = Artist::create([
            'name' => 'Creative Master',
            'bio' => 'An artist bio',
        ]);

        $artwork = Artwork::create([
            'artist_id' => $artist->id,
            'title' => 'The Void Painting',
            'medium' => 'Oil on Canvas',
            'dimensions' => '100x120cm',
            'year' => 2026,
            'status' => 'available',
            'image' => 'artworks/sample.jpg',
        ]);

        $this->actingAs($user);

        $res = $this->postJson('/api/artwork-offerings', [
            'artwork_id' => $artwork->id,
            'offering_price' => 15000000,
        ]);

        $res->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('artwork_offerings', [
            'artwork_id' => $artwork->id,
            'offering_price' => 15000000,
            'user_id' => $user->id,
        ]);
    }

    /** 7. My Orders & Cancellation */
    public function test_my_orders_and_cancellation(): void
    {
        $user = User::create([
            'name' => 'Order Customer',
            'email' => 'customer_orders@example.com',
            'password' => Hash::make('password'),
        ]);

        $product = Product::create([
            'name' => 'EXCLUSIVE TEE',
            'price' => 450000,
        ]);

        $stock = ProductStock::create([
            'product_id' => $product->id,
            'size' => 'XL',
            'stock' => 5,
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Test 123',
            'total_amount' => 450000,
            'status' => 'pending',
            'tracking_number' => 'BITESHIP123456',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'size' => 'XL',
            'quantity' => 2,
            'price' => 450000,
        ]);

        // Customer fetching their own orders
        $this->actingAs($user);
        $res = $this->getJson('/my-orders');
        $res->assertStatus(200);
        $this->assertCount(1, $res->json());
        $this->assertEquals('BITESHIP123456', $res->json()[0]['tracking_number']);

        // Cancel order restores stock
        $resCancel = $this->postJson("/my-orders/{$order->id}/cancel");
        $resCancel->assertStatus(200);

        $this->assertEquals('cancelled', $order->fresh()->status);
        $this->assertEquals(7, $stock->fresh()->stock); // 5 + 2 restored
    }
}
