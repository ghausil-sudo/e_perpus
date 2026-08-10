<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = ['kode_member', 'nama', 'nis_nip', 'jenis_kelamin', 'alamat', 'telepon', 'email', 'tanggal_daftar', 'status'];

    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }
}
