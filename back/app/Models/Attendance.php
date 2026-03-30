<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['user_id', 'session_id', 'status', 'checked_in_at'];

    protected $casts = ['checked_in_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function session() { return $this->belongsTo(Session::class); }
}
