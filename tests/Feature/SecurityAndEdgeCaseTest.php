<?php

namespace Tests\Feature;

use App\Models\Artist;
use App\Models\Artwork;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use App\Models\UserAddress;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SecurityAndEdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    /** 1. Anti Price Tampering */
    public function test_checkout_calculates_price_from_database_ignoring_client_tampering(): void
    {
        $user = User::create([
            'name' => 'Tamper Tester',
            'email' => 'tamper@example.com',
            'password' => Hash::make('password'),
        ]);

        $product = Product::create([
            'name' => 'EXPENSIVE ART HOODIE',
            'price' => 1000000, // 1 Juta Rupiah in DB
        ]);

        ProductStock::create([
            'product_id' => $product->id,
            'size' => 'L',
            'stock' => 5,
        ]);

        $this->actingAs($user);

        // Client attempts to send price = 1000 (Rp 1.000)
        $res = $this->postJson('/checkout', [
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Tamper 10',
            'destination_area_id' => 'IDNP9IDNC22IDND2044IDZ40353',
            'shipping_cost' => 10000,
            'courier_name' => 'JNE REG',
            'items' => [
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'size' => 'L',
                    'quantity' => 1,
                    'price' => 1000, // Tampered price
                ],
            ],
        ]);

        $res->assertStatus(200);

        $order = Order::where('customer_email', 'tamper@example.com')->first();
        $this->assertNotNull($order);
        // Subtotal must be 1.000.000 from database, not 1.000!
        $this->assertEquals(1000000, (int) $order->subtotal);
        $this->assertEquals(10000, (int) $order->shipping_cost);
        $this->assertEquals(1010000, (int) $order->total_amount);
    }

    /** 2. Promo Code Calculations */
    public function test_checkout_promo_code_discounts(): void
    {
        $user = User::create([
            'name' => 'Promo Tester',
            'email' => 'promo@example.com',
            'password' => Hash::make('password'),
        ]);

        $product = Product::create([
            'name' => 'TEE SHIRT',
            'price' => 200000,
        ]);

        ProductStock::create([
            'product_id' => $product->id,
            'size' => 'M',
            'stock' => 10,
        ]);

        $this->actingAs($user);

        // Test NOTISSE10 (10% discount = 20.000)
        $res = $this->postJson('/checkout', [
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Promo 1',
            'destination_area_id' => 'IDNP9IDNC22IDND2044IDZ40353',
            'shipping_cost' => 10000,
            'courier_name' => 'JNE REG',
            'promo_code' => 'NOTISSE10',
            'items' => [
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'size' => 'M',
                    'quantity' => 1,
                    'price' => 200000,
                ],
            ],
        ]);

        $res->assertStatus(200);
        $order = Order::where('customer_email', 'promo@example.com')->first();
        $this->assertEquals(20000, (int) $order->discount_amount);
        $this->assertEquals(190000, (int) $order->total_amount); // 200.000 - 20.000 + 10.000
    }

    /** 3. Pending Order Cooldown Guard (< 2 hours) */
    public function test_checkout_blocks_duplicate_order_when_pending_order_is_active(): void
    {
        $user = User::create([
            'name' => 'Active Pending User',
            'email' => 'pending_active@example.com',
            'password' => Hash::make('password'),
        ]);

        $product = Product::create([
            'name' => 'SAMPLE HOODIE',
            'price' => 300000,
        ]);

        ProductStock::create([
            'product_id' => $product->id,
            'size' => 'L',
            'stock' => 5,
        ]);

        // Existing active pending order
        Order::create([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Test 1',
            'total_amount' => 310000,
            'status' => 'pending',
            'created_at' => now()->subMinutes(30), // 30 minutes old
        ]);

        $this->actingAs($user);

        $res = $this->postJson('/checkout', [
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Test 2',
            'destination_area_id' => 'IDNP9IDNC22IDND2044IDZ40353',
            'shipping_cost' => 10000,
            'courier_name' => 'JNE REG',
            'items' => [
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'size' => 'L',
                    'quantity' => 1,
                    'price' => 300000,
                ],
            ],
        ]);

        $res->assertStatus(403)
            ->assertJson([
                'pending_order' => true,
            ]);
    }

    /** 4. Auto-cancel Expired Pending Order (> 2 hours) and Restock */
    public function test_checkout_auto_cancels_and_restocks_orders_older_than_two_hours(): void
    {
        $user = User::create([
            'name' => 'Expired Pending User',
            'email' => 'pending_expired@example.com',
            'password' => Hash::make('password'),
        ]);

        $product = Product::create([
            'name' => 'SAMPLE CAP',
            'price' => 150000,
        ]);

        $stock = ProductStock::create([
            'product_id' => $product->id,
            'size' => 'All Size',
            'stock' => 3, // Initial stock
        ]);

        $expiredOrder = Order::create([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Expired 1',
            'total_amount' => 160000,
            'status' => 'pending',
            'created_at' => Carbon::now()->subHours(3), // 3 hours ago
        ]);

        OrderItem::create([
            'order_id' => $expiredOrder->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'size' => 'All Size',
            'quantity' => 2,
            'price' => 150000,
        ]);

        $this->actingAs($user);

        $res = $this->postJson('/checkout', [
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. New Order 2',
            'destination_area_id' => 'IDNP9IDNC22IDND2044IDZ40353',
            'shipping_cost' => 10000,
            'courier_name' => 'JNE REG',
            'items' => [
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'size' => 'All Size',
                    'quantity' => 1,
                    'price' => 150000,
                ],
            ],
        ]);

        $res->assertStatus(200);

        // Expired order must be marked cancelled
        $this->assertEquals('cancelled', $expiredOrder->fresh()->status);
        // Stock: initial 3 + 2 restored from expired - 1 from new order = 4
        $this->assertEquals(4, $stock->fresh()->stock);
    }

    /** 5. Address Book Strictly Max 3 */
    public function test_address_book_strictly_limits_maximum_three_addresses(): void
    {
        $user = User::create([
            'name' => 'Address Limit User',
            'email' => 'address_limit@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($user);

        // Create 3 addresses
        for ($i = 1; $i <= 3; $i++) {
            $res = $this->postJson('/api/user/addresses', [
                'label' => "Address {$i}",
                'recipient_name' => "Recipient {$i}",
                'phone' => "0812345678{$i}",
                'address' => "Jl. Test No. {$i}",
                'area_id' => "area_{$i}",
            ]);
            $res->assertStatus(200);
        }

        $this->assertCount(3, $user->fresh()->addresses);

        // Attempt 4th address
        $res4 = $this->postJson('/api/user/addresses', [
            'label' => 'Address 4',
            'recipient_name' => 'Recipient 4',
            'phone' => '08123456784',
            'address' => 'Jl. Test No. 4',
            'area_id' => 'area_4',
        ]);

        $res4->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);

        // Still exactly 3
        $this->assertCount(3, $user->fresh()->addresses);
    }

    /** 6. Address Book Deletion Auto-promotes Next Address */
    public function test_deleting_primary_address_promotes_next_address_to_primary(): void
    {
        $user = User::create([
            'name' => 'Promote User',
            'email' => 'promote@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($user);

        $addr1 = UserAddress::create([
            'user_id' => $user->id,
            'label' => 'Home',
            'recipient_name' => 'John',
            'phone' => '08123',
            'address' => 'Jl. Home',
            'area_id' => 'area_home',
            'is_primary' => true,
        ]);

        $addr2 = UserAddress::create([
            'user_id' => $user->id,
            'label' => 'Office',
            'recipient_name' => 'John Work',
            'phone' => '08124',
            'address' => 'Jl. Office',
            'area_id' => 'area_office',
            'is_primary' => false,
        ]);

        $res = $this->deleteJson("/api/user/addresses/{$addr1->id}");
        $res->assertStatus(200);

        $this->assertTrue((bool) $addr2->fresh()->is_primary);
        $this->assertEquals('Jl. Office', $user->fresh()->address);
    }

    /** 7. Artwork Offering Unauthenticated Rejection */
    public function test_artwork_offering_requires_authenticated_user(): void
    {
        $artist = Artist::create(['name' => 'Sample Artist']);
        $artwork = Artwork::create([
            'artist_id' => $artist->id,
            'title' => 'Masterpiece',
            'image' => 'artworks/sample.jpg',
            'status' => 'available',
        ]);

        $res = $this->postJson('/api/artwork-offerings', [
            'artwork_id' => $artwork->id,
            'offering_price' => 10000000,
        ]);

        $res->assertStatus(401);
    }

    /** 8. Cart Update Rejection of Non-positive Quantity */
    public function test_cart_update_rejects_zero_or_negative_quantity(): void
    {
        $user = User::create([
            'name' => 'Cart Qty User',
            'email' => 'cart_qty@example.com',
            'password' => Hash::make('password'),
        ]);

        $product = Product::create([
            'name' => 'TSHIRT',
            'price' => 100000,
        ]);

        ProductStock::create([
            'product_id' => $product->id,
            'size' => 'M',
            'stock' => 10,
        ]);

        $this->actingAs($user);

        $addRes = $this->postJson('/api/cart', [
            'product_id' => $product->id,
            'size' => 'M',
            'quantity' => 2,
        ]);
        $addRes->assertStatus(200);
        $cartItemId = $addRes->json('items.0.cart_item_id');

        // Quantity 0
        $resZero = $this->putJson("/api/cart/{$cartItemId}", ['quantity' => 0]);
        $resZero->assertStatus(422);

        // Quantity -5
        $resNeg = $this->putJson("/api/cart/{$cartItemId}", ['quantity' => -5]);
        $resNeg->assertStatus(422);
    }

    /** 9. Non-existent IDs in SPA Routes */
    public function test_spa_detail_routes_handle_non_existent_ids_gracefully(): void
    {
        $this->get('/product/999999')->assertStatus(200);
        $this->get('/artist/999999')->assertStatus(200);
        $this->get('/artwork/999999')->assertStatus(200);
        $this->get('/editorial/999999')->assertStatus(200);
        $this->get('/lookbook/999999')->assertStatus(200);
    }

    /** 10. Order Cancellation Authorization Flaw Is Now Fully Protected */
    public function test_unauthorized_user_cannot_cancel_another_users_order(): void
    {
        $victim = User::create([
            'name' => 'Victim User',
            'email' => 'victim@example.com',
            'password' => Hash::make('password'),
        ]);

        $attacker = User::create([
            'name' => 'Attacker User',
            'email' => 'attacker@example.com',
            'password' => Hash::make('password'),
        ]);

        $order = Order::create([
            'user_id' => $victim->id,
            'customer_name' => $victim->name,
            'customer_email' => $victim->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Victim',
            'total_amount' => 500000,
            'status' => 'pending',
        ]);

        // Unauthenticated guest cannot cancel
        $resGuest = $this->postJson("/my-orders/{$order->id}/cancel");
        $resGuest->assertStatus(401);

        // Attacker is logged in
        $this->actingAs($attacker);

        // Attacker tries to cancel Victim's order -> MUST return 403 Forbidden!
        $res = $this->postJson("/my-orders/{$order->id}/cancel");
        $res->assertStatus(403);
        $this->assertEquals('pending', $order->fresh()->status);

        // Victim cancels their own order -> Allowed 200 OK
        $this->actingAs($victim);
        $resVictim = $this->postJson("/my-orders/{$order->id}/cancel");
        $resVictim->assertStatus(200);
        $this->assertEquals('cancelled', $order->fresh()->status);
    }

    /** 11. Admin Discount Code Management Lifecycle */
    public function test_admin_can_manage_discount_codes(): void
    {
        $admin = User::create([
            'name' => 'Promo Admin',
            'email' => 'admin_promo@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        // Create discount code
        $resCreate = $this->post('/admin/discounts', [
            'code' => 'WEEKEND20',
            'description' => 'Diskon Weekend 20%',
            'type' => 'percentage',
            'value' => 20,
            'min_order_amount' => 150000,
            'max_discount_amount' => 50000,
            'usage_limit' => 50,
            'is_active' => 1,
        ]);
        $resCreate->assertRedirect('/admin#section-discounts');

        $discount = DiscountCode::where('code', 'WEEKEND20')->first();
        $this->assertNotNull($discount);
        $this->assertEquals(20, $discount->value);
        $this->assertEquals(150000, $discount->min_order_amount);

        // Update discount code
        $resUpdate = $this->put("/admin/discounts/{$discount->id}", [
            'code' => 'WEEKEND25',
            'description' => 'Diskon Weekend Diperbarui 25%',
            'type' => 'percentage',
            'value' => 25,
            'min_order_amount' => 200000,
            'is_active' => 1,
        ]);
        $resUpdate->assertRedirect('/admin#section-discounts');
        $this->assertEquals('WEEKEND25', $discount->fresh()->code);
        $this->assertEquals(25, $discount->fresh()->value);

        // Toggle active status
        $resToggle = $this->post("/admin/discounts/{$discount->id}/toggle");
        $resToggle->assertRedirect('/admin#section-discounts');
        $this->assertFalse((bool) $discount->fresh()->is_active);

        // Delete discount code
        $resDelete = $this->delete("/admin/discounts/{$discount->id}");
        $resDelete->assertRedirect('/admin#section-discounts');
        $this->assertNull(DiscountCode::find($discount->id));
    }

    /** 12. Check Promo Code API Endpoint */
    public function test_api_check_promo_code_validation(): void
    {
        $code = DiscountCode::create([
            'code' => 'SUPERDEAL',
            'description' => 'Diskon 25%',
            'type' => 'percentage',
            'value' => 25,
            'min_order_amount' => 100000,
            'max_discount_amount' => 50000,
            'is_active' => true,
        ]);

        // Valid subtotal 200.000: 25% = 50.000
        $res = $this->postJson('/api/check-promo', [
            'code' => 'superdeal', // Test case-insensitivity
            'subtotal' => 200000,
        ]);
        $res->assertStatus(200)
            ->assertJson([
                'success' => true,
                'code' => 'SUPERDEAL',
                'discount_amount' => 50000,
            ]);

        // Subtotal below minimum order (50.000 < 100.000)
        $resBelow = $this->postJson('/api/check-promo', [
            'code' => 'SUPERDEAL',
            'subtotal' => 50000,
        ]);
        $resBelow->assertStatus(422)
            ->assertJson(['success' => false]);

        // Deactivated code
        $code->update(['is_active' => false]);
        $resInactive = $this->postJson('/api/check-promo', [
            'code' => 'SUPERDEAL',
            'subtotal' => 200000,
        ]);
        $resInactive->assertStatus(422);

        // Non-existent code
        $resNotFound = $this->postJson('/api/check-promo', [
            'code' => 'NONEXISTENT',
            'subtotal' => 200000,
        ]);
        $resNotFound->assertStatus(422);
    }

    /** 13. Dynamic Discount Usage In Checkout */
    public function test_checkout_applies_database_discount_code_and_increments_used_count(): void
    {
        $user = User::create([
            'name' => 'Discount Buyer',
            'email' => 'discount_buyer@example.com',
            'password' => Hash::make('password'),
        ]);

        $product = Product::create([
            'name' => 'PREMIUM COAT',
            'price' => 500000,
        ]);

        ProductStock::create([
            'product_id' => $product->id,
            'size' => 'L',
            'stock' => 5,
        ]);

        $discount = DiscountCode::create([
            'code' => 'FLASH30',
            'type' => 'percentage',
            'value' => 30,
            'min_order_amount' => 0,
            'is_active' => true,
            'used_count' => 0,
        ]);

        $this->actingAs($user);

        // Subtotal: 500.000. 30% discount = 150.000. Shipping: 15.000. Total = 365.000.
        $res = $this->postJson('/checkout', [
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Flash 30',
            'destination_area_id' => 'IDNP9IDNC22IDND2044IDZ40353',
            'shipping_cost' => 15000,
            'courier_name' => 'JNE REG',
            'promo_code' => 'FLASH30',
            'items' => [
                [
                    'id' => $product->id,
                    'name' => $product->name,
                    'size' => 'L',
                    'quantity' => 1,
                    'price' => 500000,
                ],
            ],
        ]);

        $res->assertStatus(200);

        $order = Order::where('customer_email', 'discount_buyer@example.com')->first();
        $this->assertEquals(150000, (int) $order->discount_amount);
        $this->assertEquals(365000, (int) $order->total_amount);

        // used_count must increment by 1
        $this->assertEquals(1, $discount->fresh()->used_count);
    }

    /** 14. Admin Management Lifecycle and Safeguards */
    public function test_admin_can_manage_other_admins_with_security_safeguards(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super_admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->actingAs($superAdmin);

        // 1. Admin can access admin page with admins data
        $resIndex = $this->get('/admin#section-admins');
        $resIndex->assertStatus(200);
        $resIndex->assertSee('Admin & Staff Management', false);

        // 2. Admin can create new admin
        $resCreate = $this->post('/admin/admins', [
            'name' => 'Admin Baru',
            'email' => 'admin_baru@example.com',
            'password' => 'secret123',
            'phone' => '081234567890',
        ]);
        $resCreate->assertRedirect('/admin#section-admins');
        $resCreate->assertSessionHas('success');

        $newAdmin = User::where('email', 'admin_baru@example.com')->first();
        $this->assertNotNull($newAdmin);
        $this->assertEquals('admin', $newAdmin->role);
        $this->assertTrue(Hash::check('secret123', $newAdmin->password));

        // 3. Admin can update other admin details and password
        $resUpdate = $this->put("/admin/admins/{$newAdmin->id}", [
            'name' => 'Admin Baru Updated',
            'email' => 'admin_updated@example.com',
            'password' => 'newpassword456',
            'phone' => '08987654321',
        ]);
        $resUpdate->assertRedirect('/admin#section-admins');
        $resUpdate->assertSessionHas('success');

        $updatedAdmin = $newAdmin->fresh();
        $this->assertEquals('Admin Baru Updated', $updatedAdmin->name);
        $this->assertEquals('admin_updated@example.com', $updatedAdmin->email);
        $this->assertTrue(Hash::check('newpassword456', $updatedAdmin->password));

        // 4. Admin cannot delete their own account
        $resSelfDelete = $this->delete("/admin/admins/{$superAdmin->id}");
        $resSelfDelete->assertRedirect('/admin#section-admins');
        $resSelfDelete->assertSessionHas('error');
        $this->assertNotNull($superAdmin->fresh());

        // 5. Admin can delete another admin when multiple admins exist
        $resDelete = $this->delete("/admin/admins/{$updatedAdmin->id}");
        $resDelete->assertRedirect('/admin#section-admins');
        $resDelete->assertSessionHas('success');
        $this->assertNull(User::find($updatedAdmin->id));

        // 6. Non-admin user cannot access admin management routes
        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'regular_user@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $this->actingAs($regularUser);
        $resForbidden = $this->post('/admin/admins', [
            'name' => 'Hacker Admin',
            'email' => 'hacker@example.com',
            'password' => 'secret123',
        ]);
        $resForbidden->assertStatus(403);
    }

    /** 15. Guest and Unauthorized Users Cannot Hijack / Pay Other Orders (IDOR Fix) */
    public function test_unauthenticated_or_unauthorized_user_cannot_trigger_payment_for_order(): void
    {
        $owner = User::create([
            'name' => 'Order Owner',
            'email' => 'owner@example.com',
            'password' => Hash::make('password123'),
        ]);

        $attacker = User::create([
            'name' => 'Attacker User',
            'email' => 'attacker_pay@example.com',
            'password' => Hash::make('password123'),
        ]);

        $userOrder = Order::create([
            'user_id' => $owner->id,
            'customer_name' => $owner->name,
            'customer_email' => $owner->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Owner',
            'total_amount' => 300000,
            'status' => 'pending',
        ]);

        $guestOrder = Order::create([
            'user_id' => null,
            'customer_name' => 'Guest Customer',
            'customer_email' => 'guest@example.com',
            'customer_phone' => '08123456780',
            'shipping_address' => 'Jl. Guest',
            'total_amount' => 250000,
            'status' => 'pending',
        ]);

        // 1. Unauthenticated guest cannot trigger pay on user order (401)
        $res1 = $this->postJson("/my-orders/{$userOrder->id}/pay");
        $res1->assertStatus(401);

        // 2. Unauthenticated guest cannot trigger pay on guest order either (401)
        $res2 = $this->postJson("/my-orders/{$guestOrder->id}/pay");
        $res2->assertStatus(401);

        // 3. Attacker logged in cannot pay victim's order (403)
        $this->actingAs($attacker);
        $res3 = $this->postJson("/my-orders/{$userOrder->id}/pay");
        $res3->assertStatus(403);

        // 4. Attacker logged in cannot claim guest order either (403)
        $res4 = $this->postJson("/my-orders/{$guestOrder->id}/pay");
        $res4->assertStatus(403);
    }

    /** 16. Admin Order Status Reversal Restores and Decrements Stock Accurately */
    public function test_admin_order_status_reversal_decrements_stock_back(): void
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin_stock@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $product = Product::create([
            'name' => 'T-Shirt Reversal Test',
            'price' => 150000,
        ]);

        $stock = ProductStock::create([
            'product_id' => $product->id,
            'size' => 'L',
            'stock' => 10,
        ]);

        $order = Order::create([
            'user_id' => null,
            'customer_name' => 'Buyer',
            'customer_email' => 'buyer@example.com',
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jl. Test',
            'total_amount' => 150000,
            'status' => 'pending',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'size' => 'L',
            'quantity' => 2,
            'price' => 150000,
        ]);

        $this->actingAs($admin);

        // 1. Change status from pending to cancelled -> stock should increment by 2 (10 + 2 = 12)
        $resCancel = $this->postJson("/admin/orders/{$order->id}/status", [
            'status' => 'cancelled',
        ]);
        $resCancel->assertStatus(200);
        $this->assertEquals(12, $stock->fresh()->stock);

        // 2. Change status from cancelled back to processing -> stock should decrement by 2 (12 - 2 = 10)
        $resProcessing = $this->postJson("/admin/orders/{$order->id}/status", [
            'status' => 'processing',
        ]);
        $resProcessing->assertStatus(200);
        $this->assertEquals(10, $stock->fresh()->stock);

        // 3. Change status from processing back to cancelled again -> stock incremented back to 12
        $resCancel2 = $this->postJson("/admin/orders/{$order->id}/status", [
            'status' => 'cancelled',
        ]);
        $resCancel2->assertStatus(200);
        $this->assertEquals(12, $stock->fresh()->stock);
    }

    /** 17. Admin Product Form Strictly Rejects Negative Stock and Price */
    public function test_admin_product_stock_and_price_rejects_negative_values(): void
    {
        $admin = User::create([
            'name' => 'Admin Validator',
            'email' => 'admin_validator@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $this->actingAs($admin);

        // 1. Negative price rejected on create
        $resPriceNeg = $this->post('/admin/products', [
            'name' => 'Negative Price Product',
            'price' => -50000,
            'stocks' => ['S' => 5],
        ]);
        $resPriceNeg->assertSessionHasErrors(['price']);

        // 2. Negative stock rejected on create
        $resStockNeg = $this->post('/admin/products', [
            'name' => 'Negative Stock Product',
            'price' => 100000,
            'stocks' => ['S' => -5],
        ]);
        $resStockNeg->assertSessionHasErrors(['stocks.S']);

        // 3. Valid product creates successfully
        $resValid = $this->post('/admin/products', [
            'name' => 'Valid Product',
            'price' => 125000,
            'stocks' => ['S' => 10, 'M' => 0],
        ]);
        $resValid->assertSessionHas('success');
        $product = Product::where('name', 'Valid Product')->first();
        $this->assertNotNull($product);

        // 4. Negative stock rejected on update
        $resUpdateNeg = $this->post("/admin/products/{$product->id}", [
            'name' => 'Valid Product Updated',
            'price' => 125000,
            'stocks' => ['S' => -2],
        ]);
        $resUpdateNeg->assertSessionHasErrors(['stocks.S']);
    }
}
