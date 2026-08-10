<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    protected $fillable = ['kode_penerbit', 'nama_penerbit', 'alamat', 'telepon', 'email'];
}
