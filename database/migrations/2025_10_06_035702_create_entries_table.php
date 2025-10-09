<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->date('entry_date');
            $table->text('content');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            // 生徒ごとに同じ日付の重複登録を防ぐ
            $table->unique(['student_id', 'entry_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
