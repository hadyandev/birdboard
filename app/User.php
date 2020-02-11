<?php

namespace App;

use App\Project;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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

    public function projects()
    {
        // specify owner_id sebagai foreign key. defaultnya adalah user_id

        // method yang sama

        // return $this->hasMany(Project::class, 'owner_id')->orderBy('updated_at', 'desc');
        // return $this->hasMany(Project::class, 'owner_id')->orderByDesc('updated_at');
        return $this->hasMany(Project::class, 'owner_id')->latest('updated_at'); //default created_at
    }
}
