<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    //
    protected $fillable = [
        'user_id', 'judul', 'isi', 'is_published'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
