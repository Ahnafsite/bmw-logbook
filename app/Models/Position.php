<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
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
        return $this->hasManyThrough(User::class, UserPositionAndDivision::class, 'position_id', 'id', 'id', 'user_id');
    }
}
