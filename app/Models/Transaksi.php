<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $fillable = [
        'nama', 'email', 'phone',
        'alamat', 'kota', 'kode_pos',
        'metode_pengiriman', 'catatan',
        'total_harga', 'status'
    ];

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class);
    }
}