<?php

namespace Tests\Feature;

use App\Models\ConfirmationRequest;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfirmationRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_create_request_for_selected_pharmacy_and_see_details_in_history(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $pharmacy = Pharmacy::create([
            'user_id' => User::factory()->create(['role' => 'pharmacy'])->id,
            'pharmacy_name' => 'Apotek Sehat',
            'owner_name' => 'Pemilik Apotek',
            'phone' => '081234567890',
            'address' => 'Jl. Sehat No. 1',
            'verification_status' => 'approved',
        ]);
        $medicine = Medicine::create([
            'pharmacy_id' => $pharmacy->id,
            'name' => 'Paracetamol',
            'category' => 'Pereda nyeri',
            'description' => 'Obat pereda nyeri.',
            'price' => 5000,
            'stock' => 20,
            'unit' => 'Tablet',
            'is_available' => true,
        ]);

        $response = $this->actingAs($customer)->post(route('customer.medicines.request', $medicine), [
            'pharmacy_id' => $pharmacy->id,
            'quantity' => 3,
            'note' => 'Mohon dikonfirmasi sebelum datang.',
        ]);

        $response->assertRedirect(route('customer.requests'));
        $this->assertDatabaseHas('confirmation_requests', [
            'customer_id' => $customer->id,
            'pharmacy_id' => $pharmacy->id,
            'medicine_id' => $medicine->id,
            'quantity' => 3,
            'note' => 'Mohon dikonfirmasi sebelum datang.',
            'status' => 'pending',
        ]);

        $history = $this->actingAs($customer)->get(route('customer.requests'));
        $history->assertOk()
            ->assertSee('Mohon dikonfirmasi sebelum datang.')
            ->assertSee('Jumlah: 3');
    }

    public function test_customer_cannot_create_request_for_unavailable_pharmacy_medicine(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $pharmacy = Pharmacy::create([
            'user_id' => User::factory()->create(['role' => 'pharmacy'])->id,
            'pharmacy_name' => 'Apotek Sehat',
            'owner_name' => 'Pemilik Apotek',
            'phone' => '081234567890',
            'address' => 'Jl. Sehat No. 1',
            'verification_status' => 'approved',
        ]);
        $otherPharmacy = Pharmacy::create([
            'user_id' => User::factory()->create(['role' => 'pharmacy'])->id,
            'pharmacy_name' => 'Apotek Lain',
            'owner_name' => 'Pemilik Lain',
            'phone' => '081234567891',
            'address' => 'Jl. Lain No. 2',
            'verification_status' => 'approved',
        ]);
        $medicine = Medicine::create([
            'pharmacy_id' => $pharmacy->id,
            'name' => 'Paracetamol',
            'price' => 5000,
            'stock' => 20,
            'is_available' => true,
        ]);

        $response = $this->actingAs($customer)->from(route('customer.medicines.show', $medicine))
            ->post(route('customer.medicines.request', $medicine), [
                'pharmacy_id' => $otherPharmacy->id,
                'quantity' => 1,
            ]);

        $response->assertRedirect(route('customer.medicines.show', $medicine));
        $response->assertSessionHas('error', 'Obat tidak tersedia di apotek yang dipilih.');
        $this->assertDatabaseCount('confirmation_requests', 0);
    }

    public function test_pharmacy_response_is_visible_in_customer_history(): void
    {
        [$customer, $pharmacy, $medicine] = $this->requestFixtures();
        $request = ConfirmationRequest::create([
            'customer_id' => $customer->id,
            'pharmacy_id' => $pharmacy->id,
            'medicine_id' => $medicine->id,
            'quantity' => 2,
            'note' => 'Tolong cek stok.',
            'status' => 'pending',
        ]);

        $this->actingAs($pharmacy->user)->post(route('pharmacy.requests.respond', $request), [
            'status' => 'available',
            'response_note' => 'Stok tersedia.',
        ])->assertRedirect();

        $this->actingAs($customer)->get(route('customer.requests'))
            ->assertSee('Tersedia')
            ->assertSee('Stok tersedia.');
    }

    public function test_api_returns_available_medicines_and_approved_pharmacies(): void
    {
        [$customer, $pharmacy, $medicine] = $this->requestFixtures();

        $this->getJson('/api/medicines?q=Paracetamol')
            ->assertOk()
            ->assertJsonPath('meta.count', 1)
            ->assertJsonPath('data.0.name', $medicine->name)
            ->assertJsonPath('data.0.pharmacy.name', $pharmacy->pharmacy_name);

        $this->getJson('/api/pharmacies')
            ->assertOk()
            ->assertJsonPath('meta.count', 1)
            ->assertJsonPath('data.0.name', $pharmacy->pharmacy_name);
    }

    private function requestFixtures(): array
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $pharmacyUser = User::factory()->create(['role' => 'pharmacy']);
        $pharmacy = Pharmacy::create([
            'user_id' => $pharmacyUser->id,
            'pharmacy_name' => 'Apotek Sehat',
            'owner_name' => 'Pemilik Apotek',
            'phone' => '081234567890',
            'address' => 'Jl. Sehat No. 1',
            'verification_status' => 'approved',
        ]);
        $medicine = Medicine::create([
            'pharmacy_id' => $pharmacy->id,
            'name' => 'Paracetamol',
            'category' => 'Pereda nyeri',
            'description' => 'Obat pereda nyeri.',
            'price' => 5000,
            'stock' => 20,
            'unit' => 'Tablet',
            'is_available' => true,
        ]);

        return [$customer, $pharmacy, $medicine];
    }
}
