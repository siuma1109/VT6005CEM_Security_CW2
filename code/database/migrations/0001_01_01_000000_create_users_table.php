<?php

namespace Database\Migrations;

use Core\Database\Migration\Migration;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        $this->createTable('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        $this->createTable('sessions', function ($table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        $this->dropTable('users');
        $this->dropTable('sessions');
    }
}
