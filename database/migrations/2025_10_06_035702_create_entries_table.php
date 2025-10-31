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

            // 外部キー (fk_student: entries.student_id -> students.id)
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');

            // 体調・精神状態 (SQLスキーマではTINYINTやINTを使用)
            // 3～5の値を保存するため、TINYINT (unsignedTinyInteger) が適切
            $table->unsignedTinyInteger('condition_physical')->nullable(false);
            $table->unsignedTinyInteger('condition_mental')->nullable(false);

            // 記録日と内容
            $table->date('record_date')->nullable(false);
            $table->text('content')->nullable(false);

            // 既読フラグ (SQLスキーマではTINYINT)
            $table->boolean('is_read')->default(false);

            $table->timestamps();

            // 生徒ごとに同じ日付の重複登録を防ぐ
            $table->unique(['student_id', 'record_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
