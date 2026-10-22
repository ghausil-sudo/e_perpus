<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Member extends Model
{
    protected $fillable = ['kode_member', 'nama', 'nis_nip', 'jenis_kelamin', 'alamat', 'telepon', 'email', 'tanggal_daftar', 'status'];

    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    public static function createWithGeneratedCode(array $attributes): self
    {
        return DB::transaction(function () use ($attributes) {
            $member = static::create(array_merge($attributes, [
                'kode_member' => 'TMP-' . Str::uuid(),
            ]));

            $member->update([
                'kode_member' => 'MBR-' . now()->format('Ymd') . '-' . str_pad((string) $member->id, 4, '0', STR_PAD_LEFT),
            ]);

            return $member;
        });
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
