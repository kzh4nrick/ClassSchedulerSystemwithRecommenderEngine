<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $fillable = ['academicYear', 'semester', 'course_id', 'yearLevel', 'subject_id', 'Units', 'lec', 'lab', 'faculties_id', 'classrooms_id', 'user_id'];
}
