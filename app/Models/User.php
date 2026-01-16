<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'store', 'password', 'dob', 'gender','reg_date','username', 'type', 'last_login', 'profile_image', 'parent_menu', 'chile_menu', 'status','address', 'latitute', 'longitute'
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

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user');
    }

    public function orders()
    {
        return $this->hasMany(ProductOrder::class, 'billing_user', 'id');
    }
    public function orders_new_db()
    {
        return $this->setConnection('mysql2')->hasMany(ProductOrder2::class, 'billing_user', 'id');
    }

    public function today_login_details()
    {
        $today = date('Y-m-d');
        return $this->hasOne(LoginDetails::class, 'user_id', 'id')->where('date', $today);
    }


    
}
