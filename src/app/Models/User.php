<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'password', 'role'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['email_verified_at' => 'datetime'];

    // ユーザーは「多くの勤怠情報」を持つ（1対多）
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // ユーザーは「多くの修正申請」を持つ（1対多）
    public function attendanceEdits()
    {
        return $this->hasMany(AttendanceEdit::class);
    }

    //一般ユーザーor管理者の判定する
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
