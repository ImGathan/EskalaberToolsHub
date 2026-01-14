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
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
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
