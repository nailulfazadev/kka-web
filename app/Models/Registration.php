<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'user_id', 'training_id', 'status', 'certificate_eligible', 'facility_paid',
    ];

    protected $casts = [
        'certificate_eligible' => 'boolean',
        'facility_paid' => 'boolean',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function training() { return $this->belongsTo(Training::class); }
    public function payments() { return $this->hasMany(Payment::class); }

    public function registrationPayment()
    {
        return $this->hasOne(Payment::class)->where('type', 'registration');
    }

    public function facilityPayment()
    {
        return $this->hasOne(Payment::class)->where('type', 'facility');
    }

    public function isActive(): bool { return $this->status === 'aktif'; }
    public function isCompleted(): bool { return $this->status === 'selesai'; }
}
