<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public const ADMIN = 1;
    public const DOCTOR = 2;
    public const PATIENT = 3;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
