<?php

namespace Core\Database\Migration;

interface MigrationInterface
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void;

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void;
} 