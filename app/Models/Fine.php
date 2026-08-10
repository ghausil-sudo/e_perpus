<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $fillable = ['kode_denda', 'member_id', 'loan_id', 'jumlah', 'keterangan', 'status', 'tanggal_bayar'];

    protected $casts = [
        'tanggal_bayar' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
