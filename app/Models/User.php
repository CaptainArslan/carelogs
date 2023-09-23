<?php

namespace App\Models;

use App\Models\Disease;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ACTIVE = 1;
    public const DEACTIVE = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'gender',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
        // return $this->hasOne(Role::class,);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'user_diseases', 'user_id', 'disease_id', 'id', 'id');
    }

    public function userAvatar($request)
    {
        $image = $request->file('image');
        $name = $image->hashName();
        $destination = public_path('/images');
        $image->move($destination, $name);
        return $name;
    }

    public function scopeActiveDoctors($query)
    {
        return $query->where('role_id', Role::DOCTOR)
            ->where('status', User::ACTIVE);
    }

    public function scopeWhereRoleIsDoctor($query)
    {
        return $query->where('role_id', Role::DOCTOR);
    }

    public static function clientID()
    {
        return 'zoom_client_of_user';
    }

    public static function clientSecret()
    {
        return 'zoom_client_secret_of_user';
    }

    public static function accountID()
    {
        return 'zoom_account_id_of_user';
    }
}
