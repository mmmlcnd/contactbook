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
            $table->unsignedInteger('entry_id')->comment('対象の連絡帳エントリID');
            $table->unsignedInteger('teacher_id')->index('fk_read_history_teacher')->comment('既読処理を行った担任ID');
            $table->unsignedInteger('stamp_id')->index('fk_read_history_stamp');
            $table->timestamp('stamped_at')->useCurrent()->comment('既読処理が行われた日時');

            $table->unique(['entry_id', 'teacher_id', 'stamp_id'], 'unique_entry_teacher_stamp');
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
