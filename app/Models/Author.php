<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = ['kode_penulis', 'nama_penulis', 'biografi'];
}
