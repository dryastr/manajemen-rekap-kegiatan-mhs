<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'credits',
        'department_id',
        'lecturer_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
}
