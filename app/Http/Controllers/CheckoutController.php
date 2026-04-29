<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;

class CheckoutController extends Controller
{
    /**
     * Menampilkan halaman checkout
     */
    public function index()
    {
        // Ambil data cart dari session
        $cart = session()->get('cart', []);

        // Hitung total harga
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        return view('checkout', compact('cart', 'total'));
    }

    /**
     * Proses penyimpanan transaksi
     */
    public function store(Request $request)
    {
        // 🔥 Validasi input (biar aman)
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'kode_pos' => 'required',
        ]);

        // Ambil cart dari session
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/checkout')->with('error', 'Keranjang kosong!');
        }

        // Hitung total harga
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['qty'];
        }

        // 🔥 Simpan ke tabel transaksi
        $transaksi = Transaksi::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'phone' => $request->phone,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'kode_pos' => $request->kode_pos,
            'metode_pengiriman' => $request->metode_pengiriman,
            'catatan' => $request->catatan,
            'total_harga' => $total,
            'status' => 'pending'
        ]);

        // 🔥 Simpan detail transaksi
        foreach ($cart as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $item['id'],
                'qty' => $item['qty'],
                'subtotal' => $item['harga'] * $item['qty']
            ]);
        }

        // Hapus cart setelah checkout
        session()->forget('cart');

        // Redirect ke halaman payment
        return redirect('/payment')->with('success', 'Transaksi berhasil dibuat!');
    }
}