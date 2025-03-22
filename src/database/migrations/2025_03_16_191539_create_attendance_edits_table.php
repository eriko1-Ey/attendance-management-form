<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceEditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_edits', function (Blueprint $table) {
            $table->id(); // 申請ID（主キー）
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ユーザーID（外部キー）
            $table->foreignId('attendance_id')->constrained()->onDelete('cascade'); // 勤怠情報ID（外部キー）
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending'); // 申請ステータス
            $table->text('reason'); // 修正理由
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
        Schema::dropIfExists('attendance_edits');
    }
}
