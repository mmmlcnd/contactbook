<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * マイグレーションを実行
     */
    public function up(): void
    {
        // 'students' テーブルのカラム名を変更
        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('class', 'class_name');
        });

        // 'teachers' テーブルのカラム名を変更
        Schema::table('teachers', function (Blueprint $table) {
            $table->renameColumn('class', 'class_name');
        });
    }

    /**
     * マイグレーションを元に戻す（ロールバック）
     */
    public function down(): void
    {
        // 'students' テーブルのカラム名を元に戻す
        Schema::table('students', function (Blueprint $table) {
            $table->renameColumn('class_name', 'class');
        });

        // 'teachers' テーブルのカラム名を元に戻す
        Schema::table('teachers', function (Blueprint $table) {
            $table->renameColumn('class_name', 'class');
        });
    }
};
