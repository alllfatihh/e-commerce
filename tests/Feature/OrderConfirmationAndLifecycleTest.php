<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

    public function test_user_cannot_confirm_order_not_in_shipping_status(): void
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
    }

    public function test_user_can_confirm_order_received_when_shipped_or_delivered(): void
    {
        $user = User::factory()->create();

        // 1. Shipped order
        $orderShipped = Order::create([
            'user_id' => $user->id,
            'customer_name' => 'John Doe',
            'customer_email' => $user->email,
            'customer_phone' => '08123456789',
            'shipping_address' => 'Jakarta',
            'total_amount' => 250000,
            'status' => 'shipped',
            'tracking_number' => 'JNE123456789',
        ]);

        $responseShipped = $this->actingAs($user)->postJson("/my-orders/{$orderShipped->id}/confirm-received");
        $responseShipped->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->assertEquals('completed', $orderShipped->fresh()->status);
        $this->assertNotNull($orderShipped->fresh()->received_at);

        // 2. Delivered order
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
}
