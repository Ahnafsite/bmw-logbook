<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Boot the model and assign default role.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // Assign default role to new users if no role is assigned
            if (!$user->hasAnyRole()) {
                $user->assignRole('staff');
            }
        });
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Get the user's position and division relationship
     */
    public function userPositionAndDivision()
    {
        return $this->hasOne(UserPositionAndDivision::class);
    }

    /**
     * Get the user's position
     */
    public function position()
    {
        return $this->hasOneThrough(Position::class, UserPositionAndDivision::class, 'user_id', 'id', 'id', 'position_id');
    }

    /**
     * Get the user's division
     */
    public function division()
    {
        return $this->hasOneThrough(Division::class, UserPositionAndDivision::class, 'user_id', 'id', 'id', 'division_id');
    }
}
