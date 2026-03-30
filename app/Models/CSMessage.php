<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CSMessage extends Model {
    protected $table = 'cs_messages';
    protected $fillable = ['user_id', 'session_token', 'message', 'is_bot'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
