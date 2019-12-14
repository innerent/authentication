<?php

namespace Innerent\Authentication\Models;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Support\Facades\Hash;
use Innerent\Foundation\Traits\HasLegalDocument;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Innerent\Authentication\Notifications\VerifyEmail;
use Innerent\Authentication\Notifications\ResetPassword;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens,
        Notifiable,
        GeneratesUuid,
        HasRoles,
        SoftDeletes,
        HasLegalDocument;

    protected $fillable = [
        'name', 'email', 'password', 'birthday', 'marital_status', 'profession'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'password', 'remember_token', 'id'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'uuid' => 'uuid'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }
}
