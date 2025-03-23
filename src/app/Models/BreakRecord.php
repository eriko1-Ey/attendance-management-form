<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakRecord extends Model
{
    use HasFactory;

    protected $table = 'breaks';

    protected $fillable = ['attendance_id', 'start_time', 'end_time'];

    // 休憩情報は「1つの勤怠情報」に属する（多対1）
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
