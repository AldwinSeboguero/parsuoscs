<?php

namespace App;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Userv2 extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table='users';

    protected $fillable = [

        'id','name', 'username','email', 'password','picture',
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
    public function programs(){
        return $this->belongsToMany('App\Program');
    }

    public function hasAnyRoles($roles){
        if($this->roles()->whereIn('name', $roles)->first()){
            return true;
        }

        return false;
    }
    public function designees(){
        return $this->hasMany('App\SignatoryV2'::class,'user_id','id');
 
    }
    public function designee(){
        return $this->hasOne('App\SignatoryV2'::class,'user_id','id');
 
    }
    public function hasRole($role){
        if($this->roles()->where('name', $role)->first()){
            return true;
        }

        return false;
    } 
    public function roles(){
        return $this->belongsToMany('App\Role');
    }
    public function role(){
        return $this->hasOne('App\UserRole','user_id','id');
    }
    public function userRole($id){
    
        return $this->role()->where('user_id', $id)->first();
    } 
    public function isAdmin(){
        return strtolower($this->hasRole('admin'));
    }
    public function userInformation()
    {
        return $this->name;
    }
    public function secrets()
{
    return $this->hasMany('App\Secret');
}
public function sendPasswordResetNotification($token)
{
    $this->notify(new \App\Notifications\MailResetPasswordNotification($token));
}

}
