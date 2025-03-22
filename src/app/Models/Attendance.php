<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'date', 'status', 'clock_in', 'clock_out'];

    // 勤怠情報は「1人のユーザー」に属する（多対1）
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 勤怠情報は「複数の休憩」を持つ（1対多）
    public function breaks()
    {
        return $this->hasMany(BreakRecord::class);
    }

    // 勤怠情報は「複数の修正申請」を持つ（1対多）
    public function attendanceEdits()
    {
        return $this->hasMany(AttendanceEdit::class);
    }
}
