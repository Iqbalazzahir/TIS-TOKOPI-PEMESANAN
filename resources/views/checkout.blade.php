<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<script src="https://cdn.tailwindcss.com"></script>

<title>Checkout TOKOPI</title>
</head>

<body class="bg-[#f8f5f2] text-[#2c2c2c]">

<form action="/checkout" method="POST">
@csrf

<div class="max-w-6xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-2 gap-8">

<!-- LEFT -->
<div class="space-y-6">

<h1 class="text-2xl font-bold">Halaman Pemesanan</h1>

<!-- DATA CUSTOMER -->
<div class="bg-white p-5 rounded-xl shadow">
<h2 class="font-semibold mb-3">Data Customer</h2>

<input name="nama" class="w-full border p-2 mb-3 rounded" placeholder="Nama">
<input name="email" class="w-full border p-2 mb-3 rounded" placeholder="Email">
<input name="phone" class="w-full border p-2 rounded" placeholder="No HP">
</div>

<!-- ALAMAT -->
<div class="bg-white p-5 rounded-xl shadow">
<h2 class="font-semibold mb-3">Alamat Pengiriman</h2>

<textarea name="alamat" class="w-full border p-2 mb-3 rounded" placeholder="Alamat"></textarea>

<div class="grid grid-cols-2 gap-3">
<input name="kota" class="border p-2 rounded" placeholder="Kota">
<input name="kode_pos" class="border p-2 rounded" placeholder="Kode Pos">
</div>
</div>

<!-- METODE -->
<div class="bg-white p-5 rounded-xl shadow">
<h2 class="font-semibold mb-3">Metode Pengiriman</h2>

<label class="block mb-2">
<input type="radio" name="metode_pengiriman" value="Reguler" checked>
 Reguler (2-4 hari)
</label>

<label>
<input type="radio" name="metode_pengiriman" value="Express">
 Express (1 hari)
</label>
</div>

<!-- CATATAN -->
<div class="bg-white p-5 rounded-xl shadow">
<h2 class="font-semibold mb-3">Catatan</h2>

<textarea name="catatan" class="w-full border p-2 rounded" placeholder="Catatan..."></textarea>
</div>

</div>

<!-- RIGHT -->
<div class="bg-white p-5 rounded-xl shadow h-fit">

<h2 class="font-semibold mb-4">Ringkasan Pesanan</h2>

@if(empty($cart))
<p class="text-gray-500">Keranjang kosong</p>
@endif

@foreach($cart as $item)
<div class="flex gap-4 mb-4">

<img src="{{ $item['gambar'] }}" class="w-16 h-16 rounded object-cover">

<div class="flex-1">
<p class="font-semibold">{{ $item['nama'] }}</p>
<p class="text-sm text-gray-500">Qty: {{ $item['qty'] }}</p>
</div>

<div class="text-right font-semibold">
Rp{{ number_format($item['harga'] * $item['qty']) }}
</div>

</div>
@endforeach

@php
$ongkir = 15000;
@endphp

<hr class="my-4">

<div class="flex justify-between mb-1">
<span>Subtotal</span>
<span>Rp{{ number_format($total) }}</span>
</div>

<div class="flex justify-between mb-3">
<span>Biaya Pengiriman</span>
<span>Rp{{ number_format($ongkir) }}</span>
</div>

<div class="flex justify-between bg-[#eee3d8] p-3 rounded font-bold">
<span>Total Harga</span>
<span>Rp{{ number_format($total + $ongkir) }}</span>
</div>

<button type="submit"
class="w-full mt-4 bg-[#3c2a1a] text-white py-3 rounded-full">
Lanjut ke Pembayaran
</button>

</div>

</div>

</form>

</body>
</html>