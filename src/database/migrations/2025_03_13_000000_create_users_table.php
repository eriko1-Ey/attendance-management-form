<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // ユーザーID（主キー）
            $table->string('name'); // 名前
            $table->string('email')->unique(); // メールアドレス（重複不可）
            $table->string('password'); // パスワード（ハッシュ化）
            $table->enum('role', ['user', 'admin'])->default('user'); // ユーザーの種類（デフォルトは一般ユーザー）
            $table->timestamp('email_verified_at')->nullable(); // メール認証日（NULLなら未認証）
            $table->rememberToken(); // 自動ログイン用トークン
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
