<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan_detail extends Model
{
    protected $fillable = ['loan_id', 'book_id', 'jumlah', 'kondisi'];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
