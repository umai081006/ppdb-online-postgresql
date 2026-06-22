<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //
    protected $fillable = [
        'registration_id', 'tipe_dokumen', 'cloudinary_url', 'cloudinary_public_id', 'status', 'catatan'
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
