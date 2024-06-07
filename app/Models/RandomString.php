<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RandomString extends Model
{
    use HasFactory;

    protected $fillable = ['random_string'];

    public function submissions()
    {
        return $this->hasMany(Participation::class);
    }
}

