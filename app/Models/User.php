<?php

namespace App\Models;

use App\Models\Supervisor;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'email_google',
        'password',
        'aktif',
        'foto',
        'alamat',
        'tgl_lahir',
        'no_telp',
        'google_id'
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

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'kode_supervisor', 'kode_supervisor');
    }

    public function sendPasswordResetNotification($token)
    {
        // $url = 'https://example.com/reset-password?token='.$token;
    
        $this->notify(new ResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification(){
        $this->notify(new VerifyEmailNotification());
    }
}
