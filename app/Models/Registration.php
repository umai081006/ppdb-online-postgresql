<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    //
    protected $fillable = [
        'student_id', 'jalur_pendaftaran_id', 'no_pendaftaran',
        'status', 'nilai_rata_rata', 'catatan'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function jalurPendaftaran()
    {
        return $this->belongsTo(JalurPendaftaran::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
