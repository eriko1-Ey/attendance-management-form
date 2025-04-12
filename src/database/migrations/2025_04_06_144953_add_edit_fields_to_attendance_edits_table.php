<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEditFieldsToAttendanceEditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_edits', function (Blueprint $table) {
            $table->dateTime('new_clock_in')->nullable();
            $table->dateTime('new_clock_out')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_edits', function (Blueprint $table) {
            //
        });
    }
}
