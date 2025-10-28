<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPositionAndDivision extends Model
{
    use HasFactory;

    protected $table = 'user_position_and_divisions';

    protected $fillable = [
        'user_id',
        'position_id',
        'division_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function division()
    {
        return $this->belongsTo(Division::class);
    }
}
