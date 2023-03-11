<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Role;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
    protected $fillable = [
        'user_name',
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
        'gender',
        'address',

    ];

    /**
    * The attributes that should be hidden for serialization.
    *
    * @var array<int, string>
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
    * The attributes that should be cast.
    *
    * @var array<string, string>
    */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles() : BelongsToMany {
        return $this->belongsToMany( Role::class );
    }

    public function carts() : HasMany {
        return $this->hasMany( 'App\Models\Cart' );
    }

    public function orders() : HasMany {
        return $this->hasMany( 'App\Models\Order' );
    }

    public function scopeUsers( $query ) {
        return $query->where( 'phone', '!=', '09110000000' );
    }
}
