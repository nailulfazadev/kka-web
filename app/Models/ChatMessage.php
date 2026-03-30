<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = ['training_id', 'user_id', 'message', 'reply_to_id', 'is_bot'];

    public function training() { return $this->belongsTo(Training::class); }
    public function user() { return $this->belongsTo(User::class); }
    public function replies() { return $this->hasMany(ChatMessage::class, 'reply_to_id'); }
    public function parent() { return $this->belongsTo(ChatMessage::class, 'reply_to_id'); }
}
