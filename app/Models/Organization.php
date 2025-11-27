<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ruc',
        'business_name',
        'description',
        'address',
        'phone',
        'email',
        'legal_rep_id',
        'legal_rep_name',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user')
                    ->distinct();
    }
}