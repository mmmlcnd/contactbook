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
        Schema::create('read_histories', function (Blueprint $table) {
            $table->id();

            // 1. カラム定義: foreignId() を使って UNSIGNED BIGINT 型を定義
            $table->foreignId('entry_id')->comment('対象の連絡帳エントリID');
            $table->foreignId('teacher_id')->comment('既読処理を行った担任ID');
            $table->foreignId('stamp_id');

            $table->timestamp('stamped_at')->useCurrent()->comment('既読処理が行われた日時');

            // unique制約はそのまま
            $table->unique(['entry_id', 'teacher_id', 'stamp_id'], 'unique_entry_teacher_stamp');

            $table->timestamps(); // timestamps を追加（もしなければ）
        });

        // 2. 外部キー制約の追加: テーブル作成後に制約を追加
        Schema::table('read_histories', function (Blueprint $table) {

            $table->foreign('entry_id')->references('id')->on('entries')->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade')->onUpdate('restrict');

            $table->foreign('stamp_id')->references('id')->on('stamps')->onDelete('restrict')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('read_histories');
    }
};
