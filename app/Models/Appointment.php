<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'consultant_id',
        'country_id',
        'job_id',
        'time',
        'status'
    ];

    public function client(){
        return $this->belongsTo(User::class, 'client_id', 'id');
    }

    public function consultant(){
        return $this->belongsTo(User::class, 'consultant_id', 'id');
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function job(){
        return $this->belongsTo(Job::class);
    }
}
