<?php

namespace Tests\Feature;

use App\Models\ConfirmationRequest;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PharmacyMedicineTest extends TestCase
{
    use RefreshDatabase;

    public function test_pharmacy_dashboard_requires_pharmacy_role(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $response = $this->actingAs($customer)->get('/pharmacy/dashboard');
        $response->assertStatus(403);

        $pharmacyUser = User::factory()->create(['role' => 'pharmacy']);
        $response = $this->actingAs($pharmacyUser)->get('/pharmacy/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Apotek');
    }

    public function test_pharmacy_can_submit_registration(): void
    {
        $pharmacyUser = User::factory()->create(['role' => 'pharmacy']);

        $response = $this->actingAs($pharmacyUser)->post('/pharmacy/registration', [
            'pharmacy_name' => 'Apotek Farmago Sehat',
            'owner_name' => 'apt. John Doe',
            'phone' => '08123456789',
            'address' => 'Jl. Kesehatan No. 1',
        ]);

        $response->assertRedirect('/pharmacy/registration');
        $this->assertDatabaseHas('pharmacies', [
            'user_id' => $pharmacyUser->id,
            'pharmacy_name' => 'Apotek Farmago Sehat',
            'verification_status' => 'pending',
        ]);
    }

    public function test_pharmacy_can_view_and_update_profile(): void
    {
        $pharmacyUser = User::factory()->create(['role' => 'pharmacy']);
        $pharmacy = Pharmacy::create([
            'user_id' => $pharmacyUser->id,
            'pharmacy_name' => 'Apotek Lama',
            'owner_name' => 'apt. Pemilik Lama',
            'phone' => '0811111111',
            'address' => 'Alamat Lama',
            'verification_status' => 'approved',
        ]);

        $response = $this->actingAs($pharmacyUser)->get('/pharmacy/profile');
        $response->assertStatus(200);
        $response->assertSee('Apotek Lama');

        $updateResponse = $this->actingAs($pharmacyUser)->put('/pharmacy/profile', [
            'pharmacy_name' => 'Apotek Baru',
            'owner_name' => 'apt. Pemilik Baru',
            'phone' => '0822222222',
            'address' => 'Alamat Baru',
        ]);

        $updateResponse->assertRedirect('/pharmacy/profile');
        $this->assertDatabaseHas('pharmacies', [
            'id' => $pharmacy->id,
            'pharmacy_name' => 'Apotek Baru',
            'owner_name' => 'apt. Pemilik Baru',
        ]);
    }

    public function test_pharmacy_can_perform_crud_on_medicines(): void
    {
        $pharmacyUser = User::factory()->create(['role' => 'pharmacy']);
        $pharmacy = Pharmacy::create([
            'user_id' => $pharmacyUser->id,
            'pharmacy_name' => 'Apotek Sukses',
            'owner_name' => 'apt. Jane Doe',
            'phone' => '08123456780',
            'address' => 'Jl. Merdeka No. 10',
            'verification_status' => 'approved',
        ]);

        $createResponse = $this->actingAs($pharmacyUser)->post('/pharmacy/medicines', [
            'name' => 'Ibuprofen 400 mg',
            'category' => 'Pereda Demam & Nyeri',
            'description' => 'Meredakan rasa sakit dan peradangan.',
            'price' => 7500,
            'stock' => 50,
            'unit' => 'Strip',
            'is_available' => '1',
        ]);

        $createResponse->assertRedirect('/pharmacy/medicines');
        $this->assertDatabaseHas('medicines', [
            'pharmacy_id' => $pharmacy->id,
            'name' => 'Ibuprofen 400 mg',
            'price' => 7500,
            'stock' => 50,
        ]);

        $medicine = Medicine::where('name', 'Ibuprofen 400 mg')->first();

        $indexResponse = $this->actingAs($pharmacyUser)->get('/pharmacy/medicines');
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('Ibuprofen 400 mg');

        $showResponse = $this->actingAs($pharmacyUser)->get('/pharmacy/medicines/' . $medicine->id);
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Ibuprofen 400 mg');

        $updateResponse = $this->actingAs($pharmacyUser)->put('/pharmacy/medicines/' . $medicine->id, [
            'name' => 'Ibuprofen Forte 400 mg',
            'category' => 'Pereda Demam & Nyeri',
            'description' => 'Deskripsi baru.',
            'price' => 9000,
            'stock' => 45,
            'unit' => 'Strip',
            'is_available' => '1',
        ]);

        $updateResponse->assertRedirect('/pharmacy/medicines');
        $this->assertDatabaseHas('medicines', [
            'id' => $medicine->id,
            'name' => 'Ibuprofen Forte 400 mg',
            'price' => 9000,
        ]);

        $deleteResponse = $this->actingAs($pharmacyUser)->delete('/pharmacy/medicines/' . $medicine->id);
        $deleteResponse->assertRedirect('/pharmacy/medicines');
        $this->assertDatabaseMissing('medicines', [
            'id' => $medicine->id,
        ]);
    }

    public function test_pharmacy_can_manage_stock_and_price_quickly(): void
    {
        $pharmacyUser = User::factory()->create(['role' => 'pharmacy']);
        $pharmacy = Pharmacy::create([
            'user_id' => $pharmacyUser->id,
            'pharmacy_name' => 'Apotek Sukses',
            'owner_name' => 'apt. Jane Doe',
            'phone' => '08123456780',
            'address' => 'Jl. Merdeka No. 10',
            'verification_status' => 'approved',
        ]);

        $medicine = Medicine::create([
            'pharmacy_id' => $pharmacy->id,
            'name' => 'Antasida Doen',
            'category' => 'Obat Maag',
            'price' => 3000,
            'stock' => 10,
            'unit' => 'Strip',
            'is_available' => true,
        ]);

        $pageResponse = $this->actingAs($pharmacyUser)->get('/pharmacy/stock-price');
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('Antasida Doen');

        $updateStockResponse = $this->actingAs($pharmacyUser)->put('/pharmacy/stock-price/' . $medicine->id, [
            'price' => 3500,
            'stock' => 80,
            'is_available' => '1',
        ]);

        $updateStockResponse->assertStatus(302);
        $this->assertDatabaseHas('medicines', [
            'id' => $medicine->id,
            'price' => 3500,
            'stock' => 80,
        ]);
    }

    public function test_pharmacy_can_view_and_respond_to_requests(): void
    {
        $pharmacyUser = User::factory()->create(['role' => 'pharmacy']);
        $pharmacy = Pharmacy::create([
            'user_id' => $pharmacyUser->id,
            'pharmacy_name' => 'Apotek Sukses',
            'owner_name' => 'apt. Jane Doe',
            'phone' => '08123456780',
            'address' => 'Jl. Merdeka No. 10',
            'verification_status' => 'approved',
        ]);

        $customer = User::factory()->create(['role' => 'customer']);

        $medicine = Medicine::create([
            'pharmacy_id' => $pharmacy->id,
            'name' => 'Vitamin C 500 mg',
            'category' => 'Suplemen',
            'price' => 10000,
            'stock' => 20,
            'unit' => 'Botol',
            'is_available' => true,
        ]);

        $req = ConfirmationRequest::create([
            'customer_id' => $customer->id,
            'pharmacy_id' => $pharmacy->id,
            'medicine_id' => $medicine->id,
            'quantity' => 2,
            'note' => 'Apakah tersedia?',
            'status' => 'pending',
        ]);

        $viewResponse = $this->actingAs($pharmacyUser)->get('/pharmacy/requests');
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Apakah tersedia?');

        $respondResponse = $this->actingAs($pharmacyUser)->post('/pharmacy/requests/' . $req->id . '/respond', [
            'status' => 'available',
            'response_note' => 'Stok kami ready banyak.',
        ]);

        $respondResponse->assertStatus(302);
        $this->assertDatabaseHas('confirmation_requests', [
            'id' => $req->id,
            'status' => 'available',
            'response_note' => 'Stok kami ready banyak.',
        ]);
    }
}
