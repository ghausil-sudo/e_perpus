<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $fillable = [
        'jenis',
        'judul',
        'keterangan',
        'jumlah',
        'nilai_lama',
        'nilai_baru',
        'fine_id',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fine()
    {
        return $this->belongsTo(Fine::class);
    }
}
