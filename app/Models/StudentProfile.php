<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'first_name',
        'last_name',
        'phone',
        'course',
        'year_level',
        'address',
        'profile_picture',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
