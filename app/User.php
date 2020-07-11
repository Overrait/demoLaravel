<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

use App\Role;

class User extends Authenticatable implements HasMedia
{
    use Notifiable;

    use HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'login', 'image'
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

    public function links() {
        return $this->hasMany('App\Link');
    }

    public function roles() {
        return $this->belongsToMany('App\Role', 'role_user');
    }

    public function profile() {
        return $this->hasOne('App\Profile');
    }

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100);
    }

    public function hasRole($role) {
        return (bool) $this->roles()->where('name', $role)->exists();
    }

    public function giveRole($nameRole) {
        $role = Role::where('name', $nameRole)->first();

        if($role) {
            $this->roles()->attach($role->id);
        }

        return $this;
    }

    public function deleteRole($nameRole)
    {
        $role = Role::where('name',$nameRole)->first();
        if ($role) {
            $this->roles()->detach($role->id);
        }
        return $this;
    }
}
