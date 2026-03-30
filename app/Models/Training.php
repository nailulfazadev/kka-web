<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Training extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'thumbnail', 'pricing_type',
        'price', 'facility_price', 'status', 'google_drive_link', 'whatsapp_link',
        'created_by', 'start_date', 'end_date', 'min_attendance_percent', 'facilities_released',
        'is_ecourse',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:0',
        'facility_price' => 'decimal:0',
    ];

    protected static function booted(): void
    {
        static::creating(function ($training) {
            if (empty($training->slug)) {
                $training->slug = Str::slug($training->title) . '-' . Str::random(5);
            }
        });
    }

    public function isFree(): bool { return $this->pricing_type === 'free'; }
    public function isBerbayar(): bool { return $this->pricing_type === 'berbayar'; }
    public function isDonasi(): bool { return $this->pricing_type === 'donasi'; }

    public function sessions() { return $this->hasMany(Session::class)->orderBy('session_number'); }
    public function registrations() { return $this->hasMany(Registration::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function participants() { return $this->belongsToMany(User::class, 'registrations')->withPivot('status'); }
    public function certificateTemplates() { return $this->hasMany(CertificateTemplate::class); }
    public function chatMessages() { return $this->hasMany(ChatMessage::class)->orderBy('created_at'); }
    public function ratings() { return $this->hasMany(Rating::class); }

    public function averageRating(): float
    {
        return round($this->ratings()->avg('score') ?? 0, 1);
    }

    public function activeParticipantsCount(): int
    {
        return $this->registrations()->whereIn('status', ['aktif', 'selesai'])->count();
    }
}
