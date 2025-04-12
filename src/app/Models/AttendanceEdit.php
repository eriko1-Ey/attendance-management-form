<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceEdit extends Model
{
    use HasFactory;

    // ★ JSONやレスポンスでuser・attendanceを自動で一緒に読み込む
    protected $with = ['user', 'attendance'];

    protected $fillable = ['user_id', 'attendance_id', 'status', 'reason'];

    // 修正申請は「1人のユーザー」に属する（多対1）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 修正申請は「1つの勤怠情報」に属する（多対1）
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
