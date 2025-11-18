<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes ;

    protected $fillable = [
        'username',
        'fullname',
        'password',
        'email',
        'phone',
        'role_id',
        'created_at',
        'updated_at'
    ];
    protected $dates = ['deleted_at'];

    public function role()
    {
        return $this->belongsTo(Role::class) ;
    }

    public function orders()
    {
        return $this->hasMany(Order::class) ;
    }

}