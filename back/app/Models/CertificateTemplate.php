<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertificateTemplate extends Model
{
    protected $fillable = ['training_id', 'name', 'front_image', 'back_image', 'name_position', 'is_active'];

    protected $casts = [
        'name_position' => 'array',
        'is_active' => 'boolean',
    ];

    public function training() { return $this->belongsTo(Training::class); }
}
