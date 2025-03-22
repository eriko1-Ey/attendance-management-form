<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id(); // 勤怠ID（主キー）
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ユーザーID（外部キー）
            $table->date('date'); // 勤務日
            $table->enum('status', ['before_work', 'working', 'on_break', 'after_work'])->default('before_work'); // 勤務ステータス
            $table->dateTime('clock_in')->nullable(); // 出勤時間
            $table->dateTime('clock_out')->nullable(); // 退勤時間
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}
