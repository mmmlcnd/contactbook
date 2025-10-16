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
        Schema::create('admins', function (Blueprint $table) {
            // id (int(10) UNSIGNED NOT NULL, PRIMARY KEY, AUTO_INCREMENT)
            // MySQLのint(10) UNSIGNED AUTO_INCREMENT PRIMARY KEYに対応
            $table->increments('id');

            // name (varchar(100) NOT NULL)
            $table->string('name', 100);

            // kana (varchar(100) NOT NULL)
            $table->string('kana', 100);

            // email (varchar(255) NOT NULL, UNIQUE KEY)
            $table->string('email')->unique();

            // password (varchar(255) NOT NULL)
            $table->string('password');

            // created_at (timestamp NOT NULL DEFAULT current_timestamp())
            // updated_at (timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp())
            // Larvelのデフォルトのtimestamps()はNULL許可ですが、
            // MySQLのタイムスタンプ定義に厳密に合わせるため、個別メソッドを使用します。

            // created_at: NOT NULL + DEFAULT CURRENT_TIMESTAMP
            $table->timestamp('created_at')->useCurrent();

            // updated_at: NOT NULL + DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
