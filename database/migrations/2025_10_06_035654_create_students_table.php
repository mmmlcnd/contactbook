<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // 必須情報
            $table->string('name', 100)->nullable(false); // ★ nameは必須です
            $table->string('kana', 100)->nullable(false);

            // 認証情報 (生徒もログイン可能と仮定)
            $table->string('email', 255)->unique();
            $table->string('password', 255);

            // クラス情報
            $table->unsignedTinyInteger('grade')->nullable(false);
            $table->string('class_name', 10)->nullable(false);

            // 権限情報
            $table->enum('permission', ['read', 'write'])->default('write');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
