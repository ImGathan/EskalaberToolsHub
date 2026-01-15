<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $table = 'tools';

    protected $fillable = [
        'name',
        'category_id',
        'image',
        'quantity',
        'status',
        'place_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function place()
    {
        return $this->belongsTo(Place::class, 'place_id');
    }

    public function toolsman()
    {
        return $this->category->toolsman();
    }

    public function loan()
    {
        return $this->hasMany(Loan::class, 'tool_id');
    }

}
