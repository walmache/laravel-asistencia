<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'start_at',
        'end_at',
        'status',
        'qr_code_path',
        'barcode_code',
        'face_threshold',
        'allow_face_checkin',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'allow_face_checkin' => 'boolean',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getQrCodeUrlAttribute()
    {
        return $this->qr_code_path ? asset('storage/' . $this->qr_code_path) : null;
    }

    public function getIsOngoingAttribute()
    {
        return $this->status === 'ongoing';
    }

    public function getIsScheduledAttribute()
    {
        return $this->status === 'scheduled';
    }

    public function getIsFinishedAttribute()
    {
        return $this->status === 'finished';
    }
}