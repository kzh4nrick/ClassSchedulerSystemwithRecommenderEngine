<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    protected $fillable = ['course_id', 'yearLevel', 'subject_id', 'Units', 'lec', 'lab', 'user_id'];
}
