<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'desc',
    ];

    public function userPositionAndDivisions()
    {
        return $this->hasMany(UserPositionAndDivision::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, UserPositionAndDivision::class, 'division_id', 'id', 'id', 'user_id');
    }
}
