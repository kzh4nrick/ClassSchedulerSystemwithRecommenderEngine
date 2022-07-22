<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSchedule extends Model
{
    use HasFactory;
    protected $fillable = ['schedule_id', 'day', 'startTime', 'endTime', 'subject_id', 'faculty_id', 'classroom_id', 'user_id'];

}
