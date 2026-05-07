<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_store_with_valid_input_saves_transaksi_and_redirects_to_payment(): void
    {
        $cart = [
            [
                'id' => 1,
                'nama' => 'Kopi Gayo',
                'harga' => 50000,
                'qty' => 2,
            ],
            [
                'id' => 2,
                'nama' => 'Kopi Toraja',
                'harga' => 60000,
                'qty' => 1,
            ],
        ];

        $payload = [
            'nama' => 'Budi',
            'email' => 'budi@example.com',
            'phone' => '08123456789',
            'alamat' => 'Jl. Merdeka',
            'kota' => 'Jakarta',
            'kode_pos' => '12345',
            'metode_pengiriman' => 'Reguler',
            'catatan' => 'Tolong antar pagi hari',
        ];

        $response = $this->withSession(['cart' => $cart])->post('/checkout', $payload);

        $response->assertRedirect('/payment');
        $response->assertSessionHas('success', 'Transaksi berhasil dibuat!');

        $this->assertDatabaseHas('transaksis', [
            'nama' => 'Budi',
            'email' => 'budi@example.com',
            'phone' => '08123456789',
            'alamat' => 'Jl. Merdeka',
            'kota' => 'Jakarta',
            'kode_pos' => '12345',
            'metode_pengiriman' => 'Reguler',
            'status' => 'pending',
        ]);

        $this->assertDatabaseHas('detail_transaksis', [
            'produk_id' => 1,
            'qty' => 2,
            'subtotal' => 100000,
        ]);

        $this->assertDatabaseHas('detail_transaksis', [
            'produk_id' => 2,
            'qty' => 1,
            'subtotal' => 60000,
        ]);
    }

    public function test_checkout_store_without_input_data_fails_validation_and_displays_errors(): void
    {
        $cart = [
            [
                'id' => 1,
                'nama' => 'Kopi Gayo',
                'harga' => 50000,
                'qty' => 2,
            ],
        ];

        $response = $this->withSession(['cart' => $cart])->post('/checkout', []);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));

        $errors = session('errors');

        if (is_object($errors) && method_exists($errors, 'all')) {
            $errorKeys = array_keys($errors->getMessages());
        } elseif (is_array($errors)) {
            if (isset($errors['default']['messages']) && is_array($errors['default']['messages'])) {
                $errorKeys = array_keys($errors['default']['messages']);
            } else {
                $errorKeys = array_keys($errors);
            }
        } else {
            $errorKeys = [];
        }

        $this->assertContains('nama', $errorKeys);
        $this->assertContains('email', $errorKeys);
        $this->assertContains('phone', $errorKeys);
        $this->assertContains('alamat', $errorKeys);
        $this->assertContains('kota', $errorKeys);
        $this->assertContains('kode_pos', $errorKeys);
        $this->assertContains('metode_pengiriman', $errorKeys);

        $this->assertDatabaseCount('transaksis', 0);
        $this->assertDatabaseCount('detail_transaksis', 0);
    }
}
