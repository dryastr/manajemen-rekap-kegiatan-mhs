<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentMhs extends Model
{
    use HasFactory;

    protected $table = 'parents_mhs';

    protected $fillable = [
        'user_id',
        'student_id',
        'phone_number',
        'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
