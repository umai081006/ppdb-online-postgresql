<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JalurPendaftaran extends Model
{
    //
    protected $fillable = [
        'nama_jalur', 'kuota', 'deskripsi', 'is_active'
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
