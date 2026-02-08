<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'password',
        'class',
        'access_type',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
        'department_id',
        'years_in',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'access_type' => 'integer',
            'is_active' => 'boolean',
        ];
    }


    public function department()
    {
        return $this->belongsTo(Department::class);
    }


    public function getCurrentClassAttribute()
    {

        if (!$this->years_in) {
            return $this->class ?? 'Tenaga Pendidik/Karyawan';
        }

        $now = now();
        $yearIn = (int) $this->years_in;
        $diff = $now->year - $yearIn;
        
        $grade = ($now->month >= 7) ? ($diff + 10) : ($diff + 9);

        if ($grade > 12) {
            return 'Lulus'; 
        }
        
        $deptName = $this->department ? $this->department->name : '';
        return $grade . ' ' . $deptName;
    }


    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($user) {
            if ($user->years_in) {
                $realClass = $user->current_class; 

                if ($realClass === 'Lulus') {
                    $user->delete(); 
                    return; 
                }

                if ($user->getOriginal('class') !== $realClass) {
                    $user->class = $realClass;
                    $user->saveQuietly(); 
                }

            }elseif (empty($user->class)) {
                $user->class = 'Tenaga Pendidik/Karyawan';
                $user->saveQuietly();
            }
        });
    }


}
