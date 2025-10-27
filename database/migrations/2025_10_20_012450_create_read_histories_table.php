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

            // foreignId() を使い、参照先の id と型を一致させる
            $table->foreignId('entry_id')
                ->constrained('entries')
                ->onDelete('cascade')
                ->onUpdate('restrict')
                ->comment('対象の連絡帳エントリID');

            $table->foreignId('teacher_id')
                ->constrained('teachers')
                ->onDelete('cascade')
                ->onUpdate('restrict')
                ->comment('既読処理を行った担任ID');

            $table->foreignId('stamp_id')
                ->constrained('stamps')
                ->onDelete('restrict') // エラーメッセージと同じ on delete restrict
                ->onUpdate('restrict'); // エラーメッセージと同じ on update restrict

            $table->timestamp('stamped_at')->useCurrent()->comment('既読処理が行われた日時');

            $table->unique(['entry_id', 'teacher_id', 'stamp_id'], 'unique_entry_teacher_stamp');

            $table->timestamps();
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
