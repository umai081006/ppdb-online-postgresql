<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    //
    protected $fillable = [
        'user_id', 'nik', 'nisn', 'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'agama', 'asal_sekolah', 'nama_ayah', 'nama_ibu',
        'pekerjaan_orang_tua', 'no_hp', 'alamat', 'kecamatan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
