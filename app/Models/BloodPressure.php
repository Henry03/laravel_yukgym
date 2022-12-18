<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BloodPressure extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time',
        'systolic',
        'diastolic',
        'user_id'
    ];
}
