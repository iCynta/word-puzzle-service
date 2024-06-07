<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participation extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'random_string_id', 'score'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function randomString()
    {
        return $this->belongsTo(RandomString::class);
    }

    public function words()
    {
        return $this->hasMany(Word::class);
    }
}



