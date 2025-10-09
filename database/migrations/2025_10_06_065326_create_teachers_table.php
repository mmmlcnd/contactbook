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
            $table->id(); // 主キー
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // users.id 外部キー
            $table->foreignId('class_id')->nullable()->constrained()->onDelete('set null'); // classes.id 外部キー（クラス未所属も可）
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
