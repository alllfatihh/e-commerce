<?php

namespace Tests\Feature;

use App\Http\Controllers\CheckoutController;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OrderConfirmationAndLifecycleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_confirm_order_received(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'customer_name' => 'John Doe',
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jakarta',
            'total_amount' => 150000,
            'status' => 'shipped',
        ]);

        $response = $this->postJson("/my-orders/{$order->id}/confirm-received");
        $response->assertStatus(401);
        $this->assertEquals('shipped', $order->fresh()->status);
        $this->assertNull($order->fresh()->received_at);
    }

    public function test_user_cannot_confirm_another_users_order(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $orderA = Order::create([
            'user_id' => $userA->id,
            'customer_name' => 'User A',
            'customer_email' => $userA->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jakarta',
            'total_amount' => 200000,
            'status' => 'shipped',
        ]);

        $response = $this->actingAs($userB)->postJson("/my-orders/{$orderA->id}/confirm-received");
        $response->assertStatus(403);
        $this->assertEquals('shipped', $orderA->fresh()->status);
        $this->assertNull($orderA->fresh()->received_at);
    }

    public function test_user_cannot_confirm_order_when_not_delivered_yet(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'customer_name' => 'John Doe',
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jakarta',
            'total_amount' => 150000,
            'status' => 'processing',
        ]);

        // Attempt when still processing
        $response = $this->actingAs($user)->postJson("/my-orders/{$order->id}/confirm-received");
        $response->assertStatus(422);
        $this->assertEquals('processing', $order->fresh()->status);
        $this->assertNull($order->fresh()->received_at);

        // Attempt when still pending
        $order->update(['status' => 'pending']);
        $responsePending = $this->actingAs($user)->postJson("/my-orders/{$order->id}/confirm-received");
        $responsePending->assertStatus(422);

        // Attempt when paid
        $order->update(['status' => 'paid']);
        $responsePaid = $this->actingAs($user)->postJson("/my-orders/{$order->id}/confirm-received");
        $responsePaid->assertStatus(422);

        // Attempt when shipped (in transit, not delivered yet)
        $order->update(['status' => 'shipped', 'biteship_status' => 'in_transit']);
        $responseShipped = $this->actingAs($user)->postJson("/my-orders/{$order->id}/confirm-received");
        $responseShipped->assertStatus(422);
        $this->assertEquals('shipped', $order->fresh()->status);
        $this->assertNull($order->fresh()->received_at);
    }

    public function test_user_can_confirm_order_received_only_when_delivered(): void
    {
        $user = User::factory()->create();

        // 1. Delivered order by web status
        $orderDelivered = Order::create([
            'user_id' => $user->id,
            'customer_name' => 'John Doe',
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jakarta',
            'total_amount' => 180000,
            'status' => 'delivered',
            'tracking_number' => 'SICEPAT987654',
        ]);

        $responseDelivered = $this->actingAs($user)->postJson("/my-orders/{$orderDelivered->id}/confirm-received");
        $responseDelivered->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertEquals('completed', $orderDelivered->fresh()->status);
        $this->assertNotNull($orderDelivered->fresh()->received_at);

        // 2. Delivered order by Biteship status
        $orderBiteshipDelivered = Order::create([
            'user_id' => $user->id,
            'customer_name' => 'John Doe 2',
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jakarta',
            'total_amount' => 220000,
            'status' => 'shipped',
            'biteship_order_id' => 'BS-123456',
            'biteship_status' => 'delivered',
            'tracking_number' => 'JNE776655',
        ]);

        $responseBsDelivered = $this->actingAs($user)->postJson("/my-orders/{$orderBiteshipDelivered->id}/confirm-received");
        $responseBsDelivered->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertEquals('completed', $orderBiteshipDelivered->fresh()->status);
        $this->assertEquals('delivered', $orderBiteshipDelivered->fresh()->biteship_status);
        $this->assertNotNull($orderBiteshipDelivered->fresh()->received_at);
    }

    public function test_admin_can_manage_order_status_pipeline_including_completed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'customer_name' => 'Buyer Test',
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Bandung',
            'total_amount' => 300000,
            'status' => 'paid',
        ]);

        // 1. Admin updates paid -> processing
        $resProc = $this->actingAs($admin)->post("/admin/orders/{$order->id}/status", [
            'status' => 'processing',
        ]);
        $resProc->assertRedirect('/admin');
        $this->assertEquals('processing', $order->fresh()->status);

        // 2. Admin updates processing -> shipped with tracking number
        $resShip = $this->actingAs($admin)->post("/admin/orders/{$order->id}/status", [
            'status' => 'shipped',
            'tracking_number' => 'NTS-TRACK-999',
        ]);
        $resShip->assertRedirect('/admin');
        $this->assertEquals('shipped', $order->fresh()->status);
        $this->assertEquals('NTS-TRACK-999', $order->fresh()->tracking_number);

        // 3. Admin updates shipped -> delivered
        $resDeliv = $this->actingAs($admin)->post("/admin/orders/{$order->id}/status", [
            'status' => 'delivered',
        ]);
        $resDeliv->assertRedirect('/admin');
        $this->assertEquals('delivered', $order->fresh()->status);

        // 4. Admin updates to completed
        $resComp = $this->actingAs($admin)->post("/admin/orders/{$order->id}/status", [
            'status' => 'completed',
        ]);
        $resComp->assertRedirect('/admin');
        $this->assertEquals('completed', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->received_at);
    }

    public function test_my_orders_endpoint_returns_received_at_and_status(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'customer_name' => 'John Doe',
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jakarta',
            'total_amount' => 150000,
            'status' => 'completed',
            'received_at' => now(),
        ]);

        $response = $this->actingAs($user)->getJson('/my-orders');
        $response->assertStatus(200);
        $this->assertCount(1, $response->json());
        $this->assertEquals('completed', $response->json()[0]['status']);
        $this->assertNotNull($response->json()[0]['received_at']);
    }

    public function test_sync_biteship_order_status_updates_tracking_and_status(): void
    {
        Http::fake([
            'https://api.biteship.com/v1/orders/BS-TEST-99' => Http::response([
                'success' => true,
                'id' => 'BS-TEST-99',
                'status' => 'in_transit',
                'courier' => [
                    'waybill_id' => 'WYB-LIVE-SYNC-12345',
                    'tracking_id' => 'TRK-998877',
                    'link' => 'https://track.biteship.com/TRK-998877',
                ],
            ], 200),
        ]);

        $order = Order::create([
            'customer_name' => 'Test Biteship Buyer',
            'customer_email' => 'buyer@example.com',
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jakarta',
            'total_amount' => 200000,
            'status' => 'paid',
            'biteship_order_id' => 'BS-TEST-99',
            'tracking_number' => 'WYB-OLD-INITIAL',
        ]);

        $controller = new CheckoutController;
        $result = $controller->syncBiteshipOrderStatus($order);

        $this->assertNotNull($result);
        $this->assertEquals('in_transit', $order->fresh()->biteship_status);
        $this->assertEquals('WYB-LIVE-SYNC-12345', $order->fresh()->tracking_number);
        $this->assertEquals('https://track.biteship.com/TRK-998877', $order->fresh()->biteship_tracking_link);
        $this->assertEquals('shipped', $order->fresh()->status);
    }

    public function test_admin_can_trigger_biteship_sync(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Http::fake([
            'https://api.biteship.com/v1/orders/BS-TEST-ADMIN' => Http::response([
                'success' => true,
                'id' => 'BS-TEST-ADMIN',
                'status' => 'delivered',
                'courier' => [
                    'waybill_id' => 'WYB-DELIVERED-555',
                    'link' => 'https://track.biteship.com/555',
                ],
            ], 200),
        ]);

        $order = Order::create([
            'customer_name' => 'Admin Test Sync',
            'customer_email' => 'admin-test@example.com',
            'customer_phone' => '08123456789',
            'shipping_address' => 'Surabaya',
            'total_amount' => 350000,
            'status' => 'shipped',
            'biteship_order_id' => 'BS-TEST-ADMIN',
            'tracking_number' => 'WYB-OLD',
        ]);

        $response = $this->actingAs($admin)->post("/admin/orders/{$order->id}/sync-biteship");
        $response->assertRedirect('/admin');

        $this->assertEquals('delivered', $order->fresh()->biteship_status);
        $this->assertEquals('delivered', $order->fresh()->status);
        $this->assertEquals('WYB-DELIVERED-555', $order->fresh()->tracking_number);
    }

    public function test_biteship_webhook_updates_order_status(): void
    {
        Http::fake([
            'https://api.biteship.com/v1/orders/BS-HOOK-123' => Http::response([
                'success' => true,
                'id' => 'BS-HOOK-123',
                'status' => 'delivered',
                'courier' => [
                    'waybill_id' => 'WYB-FINAL-777',
                    'link' => 'https://track.biteship.com/777',
                ],
            ], 200),
        ]);

        $order = Order::create([
            'customer_name' => 'Webhook Test Buyer',
            'customer_email' => 'webhook@example.com',
            'customer_phone' => '08123456789',
            'shipping_address' => 'Semarang',
            'total_amount' => 120000,
            'status' => 'shipped',
            'biteship_order_id' => 'BS-HOOK-123',
        ]);

        $response = $this->postJson('/biteship/webhook', [
            'event' => 'order.status',
            'order_id' => 'BS-HOOK-123',
            'status' => 'delivered',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'order_id' => $order->id,
                'biteship_status' => 'delivered',
                'order_status' => 'delivered',
                'tracking_number' => 'WYB-FINAL-777',
            ]);

        $this->assertEquals('delivered', $order->fresh()->status);
        $this->assertEquals('delivered', $order->fresh()->biteship_status);
        $this->assertEquals('WYB-FINAL-777', $order->fresh()->tracking_number);
    }
}
