<?php

namespace App\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    protected $appends = [
        'user_role','balance'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function payments() {
        return $this->hasMany(Payment::class);
    }

    public function sales() {
        return $this->hasMany(Sale::class);
    }

    public function balance() {
        return $this->hasOne(Balance::class);
    }

    public function getUserRoleAttribute() {
        return $this->roles->pluck('name','name')->all();
    }

    public function getBalanceAttribute() {
        $balance = $this->balance()->first();
        if ($balance) {
            return $balance->amount;
        }
        return 0;
    }
}
