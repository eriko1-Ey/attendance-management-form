<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBreaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('breaks', function (Blueprint $table) {
            $table->id(); // 休憩ID（主キー）
            $table->foreignId('attendance_id')->constrained()->onDelete('cascade'); // 勤怠情報ID（外部キー）
            $table->dateTime('start_time'); // 休憩開始時間
            $table->dateTime('end_time')->nullable(); // 休憩終了時間
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
        Schema::dropIfExists('breaks');
    }
}
