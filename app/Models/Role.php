<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes ;

    protected $fillable = ['role_name' , 'description'] ;
    protected $dates = ['deleted_at'];

    public function users()
    {
        return $this->hasMany(User::class) ;
    }
}
