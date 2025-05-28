<?php

namespace Database\Migrations;

use Core\Database\Migration\Blueprint;
use Core\Database\Migration\Migration;

class CreateAppointmentsTable extends Migration
{
    public function up(): void
    {
        $this->createTable('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('english_first_name');
            $table->string('english_last_name');
            $table->string('hkid');
            $table->string('appointment_date');
            $table->string('appointment_time');
            $table->string('venue');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->dropTable('appointments');
    }
}
