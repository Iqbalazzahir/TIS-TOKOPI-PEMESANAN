<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;

Route::get('/', function () {
    return view('welcome');
});


// 🔥 TEST CART (biar ada data dulu)
Route::get('/test-cart', function () {
    session()->put('cart', [
        [
            'id' => 1,
            'nama' => 'Kopi Gayo',
            'harga' => 50000,
            'qty' => 2
        ],
        [
            'id' => 2,
            'nama' => 'Kopi Toraja',
            'harga' => 60000,
            'qty' => 1
        ]
    ]);

    return "Cart berhasil dibuat!";
});

Route::get('/test-cart', function () {
    session()->put('cart', [
        [
            'id' => 1,
            'nama' => 'Aceh Gayo Dark Roast',
            'harga' => 125000,
            'qty' => 1,
            'gambar' => 'https://via.placeholder.com/80'
        ],
        [
            'id' => 2,
            'nama' => 'Toraja Kalosi Specialty',
            'harga' => 145000,
            'qty' => 2,
            'gambar' => 'https://via.placeholder.com/80'
        ]
    ]);

    return "Dummy cart berhasil dibuat!";
});


//  CHECKOUT
Route::get('/checkout', [CheckoutController::class, 'index']);
Route::post('/checkout', [CheckoutController::class, 'store']);


//  PAYMENT (dummy dulu)
Route::get('/payment', function () {
    return "Halaman Payment (Dummy)";
});