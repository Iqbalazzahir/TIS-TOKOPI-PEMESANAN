<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;

class CheckoutController extends Controller
{
    /**
     * Tampilkan halaman checkout
     */
    public function index()
    {
        // Ambil cart dari session (dummy / real)
        $cart = session()->get('cart', []);

        // Hitung subtotal
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        return view('checkout', compact('cart', 'total'));
    }

    /**
     * Proses checkout (simpan transaksi)
     */
    public function store(Request $request)
    {
        // 🔥 VALIDASI
        $validated = $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'kode_pos' => 'required',
            'metode_pengiriman' => 'required'
        ], [
            'nama.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'phone.required' => 'Nomor HP wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
            'kota.required' => 'Kota wajib diisi',
            'kode_pos.required' => 'Kode pos wajib diisi',
            'metode_pengiriman.required' => 'Pilih metode pengiriman'
        ]);

        // Ambil cart
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/checkout')->with('error', 'Keranjang kosong!');
        }

        // Hitung subtotal
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['harga'] * $item['qty'];
        }

        // 🔥 Ongkir berdasarkan pilihan
        $ongkir = $validated['metode_pengiriman'] === 'Express' ? 35000 : 15000;

        $total = $subtotal + $ongkir;

        // 🔥 Simpan transaksi
        $transaksi = Transaksi::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'alamat' => $validated['alamat'],
            'kota' => $validated['kota'],
            'kode_pos' => $validated['kode_pos'],
            'metode_pengiriman' => $validated['metode_pengiriman'],
            'catatan' => $request->catatan, // optional
            'total_harga' => $total,
            'status' => 'pending'
        ]);

        // 🔥 Simpan detail transaksi
        foreach ($cart as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $item['id'], // dummy OK
                'qty' => $item['qty'],
                'subtotal' => $item['harga'] * $item['qty']
            ]);
        }

        // 🔥 Hapus cart
        session()->forget('cart');

        // 🔥 Balik ke checkout + trigger popup sukses
        return redirect('/checkout')->with('success', 'Transaksi berhasil dibuat!');
    }
}