<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    protected $table = 'types';

    protected $fillable = [
        'name',
    ];

    public function tools()
    {
        return $this->hasMany(Tool::class, 'type_id');
    }
}
