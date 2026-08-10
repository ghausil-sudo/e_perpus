<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    protected $fillable = ['kode_rak', 'nama_rak', 'lokasi', 'tipe', 'kapasitas', 'deskripsi'];
}
