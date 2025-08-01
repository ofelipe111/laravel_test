<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['status', 'location_id', 'patient_id'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
