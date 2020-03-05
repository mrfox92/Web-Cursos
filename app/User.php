<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    protected static function boot () {
        parent::boot();
        static::creating( function( User $user) {
            //  si estamos añadiendo datos desde la consola
            if ( !\App::runningInConsole() ) {
                $user_slug = $user->name . " " . $user->last_name; 
                $user->slug = Str::slug($user_slug, '-');
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     * Nos ayuda a evitar problemas de seguridad por asignación masiva
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

    //  comprobamos si el usuario esta autenticado y cual archivo de navegacion deberá ser cargado segun el role del usuario
    public static function navigation () {

        return auth()->check() ? auth()->user()->role->name : 'guest';
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //  un usuario solo puede tener un rol asignado - Relacion uno a uno
    public function role() {
        return $this->belongsTo(Role::class);
    }

    //  un estudiante es tambien un usuario
    public function student () {
        return $this->hasOne(Student::class);
    }
    //  un profesor es tambien un usuario
    public function teacher () {
        return $this->hasOne(Teacher::class);
    }

    //  un usuario puede autenticarse con una red social
    public function socialAccount () {
        return $this->hasOne(UserSocialAccount::class);
    }

}
