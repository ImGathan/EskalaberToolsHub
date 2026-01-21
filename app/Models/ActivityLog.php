<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{

    protected $fillable = [
        'user_id',
        'activity',
        'description',
        'role'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // app/Models/ActivityLog.php

    public static function record($activity, $description) // Cuma ada 2 di sini
    {
        if (auth()->check()) {
            static::create([
                'user_id'     => auth()->id(),
                'activity'    => $activity,
                'description' => $description,
                'role'        => auth()->user()->access_type, // Role diambil otomatis di sini
            ]);
        }
    }
    
}
