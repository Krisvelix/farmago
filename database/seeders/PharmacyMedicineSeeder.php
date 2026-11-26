<?php

namespace Database\Seeders;

use App\Models\ConfirmationRequest;
use App\Models\Medicine;
use App\Models\Pharmacy;
use App\Models\User;
use Illuminate\Database\Seeder;

class PharmacyMedicineSeeder extends Seeder
{
    public function run(): void
    {
        $pharmacyUser = User::updateOrCreate(
            ['email' => 'apotek@farmago.com'],
            [
                'name' => 'Apotek Sehat Sentosa',
                'password' => 'password',
                'role' => 'pharmacy',
                'is_active' => true,
            ]
        );

        $pharmacy = Pharmacy::updateOrCreate(
            ['user_id' => $pharmacyUser->id],
            [
                'pharmacy_name' => 'Apotek Sehat Sentosa',
                'owner_name' => 'apt. Budi Santoso, S.Farm',
                'phone' => '081234567890',
                'address' => 'Jl. Teuku Umar No. 12, Bandar Lampung',
                'verification_status' => 'approved',
                'rejection_reason' => null,
            ]
        );

        $customerUser = User::updateOrCreate(
            ['email' => 'customer@farmago.com'],
            [
                'name' => 'Ahmad Customer',
                'password' => 'password',
                'role' => 'customer',
                'is_active' => true,
            ]
        );

        $med1 = Medicine::updateOrCreate(
            ['pharmacy_id' => $pharmacy->id, 'name' => 'Paracetamol 500 mg'],
            [
                'category' => 'Pereda Demam & Nyeri',
                'description' => 'Obat untuk meredakan demam dan nyeri ringan hingga sedang seperti sakit kepala dan sakit gigi.',
                'price' => 5000,
                'stock' => 120,
                'unit' => 'Strip',
                'is_available' => true,
            ]
        );

        $med2 = Medicine::updateOrCreate(
            ['pharmacy_id' => $pharmacy->id, 'name' => 'Amoxicillin 500 mg'],
            [
                'category' => 'Antibiotik',
                'description' => 'Antibiotik spektrum luas untuk mengatasi berbagai infeksi bakteri.',
                'price' => 15000,
                'stock' => 8,
                'unit' => 'Strip',
                'is_available' => true,
            ]
        );

        $med3 = Medicine::updateOrCreate(
            ['pharmacy_id' => $pharmacy->id, 'name' => 'Cetirizine 10 mg'],
            [
                'category' => 'Antihistamin & Alergi',
                'description' => 'Meredakan gejala alergi seperti biduran, gatal, bersin-bersin, dan hidung tersumbat.',
                'price' => 8000,
                'stock' => 0,
                'unit' => 'Strip',
                'is_available' => false,
            ]
        );

        $med4 = Medicine::updateOrCreate(
            ['pharmacy_id' => $pharmacy->id, 'name' => 'Antasida Doen'],
            [
                'category' => 'Obat Maag & Lambung',
                'description' => 'Meredakan rasa mual, nyeri lambung, dan rasa panas di dada akibat asam lambung berlebih.',
                'price' => 3000,
                'stock' => 75,
                'unit' => 'Strip',
                'is_available' => true,
            ]
        );

        $med5 = Medicine::updateOrCreate(
            ['pharmacy_id' => $pharmacy->id, 'name' => 'Vitamin C 500 mg'],
            [
                'category' => 'Vitamin & Suplemen',
                'description' => 'Membantu memelihara daya tahan tubuh dan mempercepat pemulihan saat sakit.',
                'price' => 12000,
                'stock' => 200,
                'unit' => 'Botol',
                'is_available' => true,
            ]
        );

        ConfirmationRequest::updateOrCreate(
            [
                'customer_id' => $customerUser->id,
                'pharmacy_id' => $pharmacy->id,
                'medicine_id' => $med1->id,
                'note' => 'Apakah stok masih ada untuk diambil sore ini?',
            ],
            [
                'quantity' => 2,
                'status' => 'pending',
                'response_note' => null,
                'responded_at' => null,
            ]
        );

        ConfirmationRequest::updateOrCreate(
            [
                'customer_id' => $customerUser->id,
                'pharmacy_id' => $pharmacy->id,
                'medicine_id' => $med2->id,
                'note' => 'Perlu 1 strip amoxicillin segera.',
            ],
            [
                'quantity' => 1,
                'status' => 'available',
                'response_note' => 'Stok tersedia di apotek, silakan datang langsung membawa resep.',
                'responded_at' => now()->subHours(2),
            ]
        );

        ConfirmationRequest::updateOrCreate(
            [
                'customer_id' => $customerUser->id,
                'pharmacy_id' => $pharmacy->id,
                'medicine_id' => $med3->id,
                'note' => 'Mencari cetirizine untuk alergi.',
            ],
            [
                'quantity' => 1,
                'status' => 'unavailable',
                'response_note' => 'Mohon maaf saat ini stok sedang kosong, estimasi restock besok.',
                'responded_at' => now()->subDay(),
            ]
        );
    }
}
