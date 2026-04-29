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

<!-- 🔴 ALERT ERROR -->
@if ($errors->any())
<div class="bg-red-100 text-red-700 p-4 rounded">
    <ul>
        @foreach ($errors->all() as $error)
            <li>• {{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<!-- DATA CUSTOMER -->
<div class="bg-white p-5 rounded-xl shadow">
<h2 class="font-semibold mb-3">Data Customer</h2>

<input name="nama" value="{{ old('nama') }}"
class="w-full border p-2 mb-2 rounded @error('nama') border-red-500 @enderror"
placeholder="Nama">

<input name="email" value="{{ old('email') }}"
class="w-full border p-2 mb-2 rounded @error('email') border-red-500 @enderror"
placeholder="Email">

<input name="phone" value="{{ old('phone') }}"
class="w-full border p-2 rounded @error('phone') border-red-500 @enderror"
placeholder="No HP">
</div>

<!-- ALAMAT -->
<div class="bg-white p-5 rounded-xl shadow">
<h2 class="font-semibold mb-3">Alamat Pengiriman</h2>

<textarea name="alamat"
class="w-full border p-2 mb-2 rounded @error('alamat') border-red-500 @enderror"
placeholder="Alamat">{{ old('alamat') }}</textarea>

<div class="grid grid-cols-2 gap-3">
<input name="kota" value="{{ old('kota') }}"
class="border p-2 rounded @error('kota') border-red-500 @enderror"
placeholder="Kota">

<input name="kode_pos" value="{{ old('kode_pos') }}"
class="border p-2 rounded @error('kode_pos') border-red-500 @enderror"
placeholder="Kode Pos">
</div>
</div>

<!-- METODE -->
<div class="bg-white p-5 rounded-xl shadow">
<h2 class="font-semibold mb-3">Metode Pengiriman</h2>

<label class="block mb-2">
<input type="radio" name="metode_pengiriman" value="Reguler"
data-harga="15000"
{{ old('metode_pengiriman', 'Reguler') == 'Reguler' ? 'checked' : '' }}>
 Reguler (Rp15.000)
</label>

<label>
<input type="radio" name="metode_pengiriman" value="Express"
data-harga="35000"
{{ old('metode_pengiriman') == 'Express' ? 'checked' : '' }}>
 Express (Rp35.000)
</label>
</div>

<!-- CATATAN -->
<div class="bg-white p-5 rounded-xl shadow">
<h2 class="font-semibold mb-3">Catatan</h2>

<textarea name="catatan"
class="w-full border p-2 rounded"
placeholder="Catatan...">{{ old('catatan') }}</textarea>
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

<hr class="my-4">

<div class="flex justify-between mb-1">
<span>Subtotal</span>
<span id="subtotal">Rp{{ number_format($total) }}</span>
</div>

<div class="flex justify-between mb-3">
<span>Biaya Pengiriman</span>
<span id="ongkir">Rp15.000</span>
</div>

<div class="flex justify-between bg-[#eee3d8] p-3 rounded font-bold">
<span>Total Harga</span>
<span id="total">Rp{{ number_format($total + 15000) }}</span>
</div>

<button type="submit"
class="w-full mt-4 bg-[#3c2a1a] text-white py-3 rounded-full">
Lanjut ke Pembayaran
</button>

</div>

</div>

</form>

<!-- 🔥 POPUP SUKSES -->
@if(session('success'))
<div id="popupSuccess" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-xl text-center w-80 shadow-lg">
        <div class="text-green-600 text-4xl mb-2">✔</div>
        <h2 class="text-lg font-semibold mb-2">Pesanan Sukses</h2>
        <p class="text-sm text-gray-500 mb-4">
            Pesanan berhasil disimpan ke database.
        </p>
        <button onclick="closePopup()"
            class="bg-[#3c2a1a] text-white px-4 py-2 rounded">
            OK
        </button>
    </div>
</div>
@endif

<!-- 🔥 SCRIPT ONGKIR -->
<script>
    const radios = document.querySelectorAll('input[name="metode_pengiriman"]');
    const subtotal = {{ $total }};
    const ongkirText = document.getElementById('ongkir');
    const totalText = document.getElementById('total');

    radios.forEach(radio => {
        radio.addEventListener('change', function () {
            const ongkir = parseInt(this.dataset.harga);
            ongkirText.innerText = "Rp" + ongkir.toLocaleString();
            totalText.innerText = "Rp" + (subtotal + ongkir).toLocaleString();
        });
    });

    function closePopup() {
        document.getElementById('popupSuccess').style.display = 'none';
    }
</script>

</body>
</html>