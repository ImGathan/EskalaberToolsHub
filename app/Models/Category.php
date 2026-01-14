<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'toolsman_id',
    ];

    public function toolsman()
    {
        return $this->belongsTo(User::class, 'toolsman_id');
    }

    public function tools()
    {
        return $this->hasMany(Tool::class, 'category_id');
    }

}
