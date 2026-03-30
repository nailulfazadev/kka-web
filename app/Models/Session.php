<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $table = 'training_sessions';

    protected $fillable = [
        'training_id', 'session_number', 'title', 'session_date',
        'start_time', 'end_time', 'zoom_link', 'recording_link', 'material_link', 'facilities'
    ];

    protected $casts = [
        'session_date' => 'date',
        'facilities' => 'array',
    ];

    public function training() { return $this->belongsTo(Training::class); }
    public function attendances() { return $this->hasMany(Attendance::class); }

    public function isLive(): bool
    {
        $now = now();
        return $this->session_date->isToday()
            && $now->format('H:i') >= $this->start_time
            && $now->format('H:i') <= $this->end_time;
    }

    public function isPast(): bool
    {
        return $this->session_date->isPast() && !$this->isLive();
    }

    public function isUpcoming(): bool
    {
        return $this->session_date->isFuture() || ($this->session_date->isToday() && now()->format('H:i') < $this->start_time);
    }
}
