<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes , HasFactory ;

    protected $fillable = ['name' , 'description' , 'price' , 'tax' , 'category_id' , 'img' , 'is_active'];

    protected $date = ['delete_at'] ;

    public function category()
    {
        return $this->belongsTo(Category::class) ;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class) ;
    }
}
