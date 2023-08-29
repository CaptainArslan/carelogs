<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public const ADMIN = 1;
    public const ADMINROLE = 'admin';
    public const DOCTOR = 2;
    public const DOCTORROLE = 'doctor';
    public const PATIENT = 3;
    public const PATIENTROLE = 'patient';

    protected $guarded = [];

    public function user()
    {
        return $this->hasOne(User::class);
        // return $this->belongsTo(User::class);
    }
}
