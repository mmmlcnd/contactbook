<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(false);
            $table->string('kana', 100)->nullable(false);

            // 認証情報
            $table->string('email', 255)->unique(); // UNIQUEが必須
            $table->string('password', 255);

            // 担当クラス情報 (複合キーとして使用)
            $table->unsignedTinyInteger('grade')->nullable(false);
            $table->string('class_name', 10)->nullable(false);

            // 権限情報
            $table->enum('permission', ['read', 'full'])->default('read');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
