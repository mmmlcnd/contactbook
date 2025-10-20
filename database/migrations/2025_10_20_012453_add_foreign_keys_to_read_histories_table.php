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
        Schema::table('read_histories', function (Blueprint $table) {
            $table->foreign(['entry_id'], 'fk_read_history_entry')->references(['id'])->on('entries')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['stamp_id'], 'fk_read_history_stamp')->references(['id'])->on('stamps')->onUpdate('restrict')->onDelete('restrict');
            $table->foreign(['teacher_id'], 'fk_read_history_teacher')->references(['id'])->on('teachers')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('read_histories', function (Blueprint $table) {
            $table->dropForeign('fk_read_history_entry');
            $table->dropForeign('fk_read_history_stamp');
            $table->dropForeign('fk_read_history_teacher');
        });
    }
};
