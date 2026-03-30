<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = ['training_id', 'user_id', 'message'];

    public function training() { return $this->belongsTo(Training::class); }
    public function user() { return $this->belongsTo(User::class); }
}
