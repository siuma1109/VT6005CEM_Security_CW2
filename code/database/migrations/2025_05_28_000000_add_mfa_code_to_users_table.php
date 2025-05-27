<?php

namespace Database\Migrations;

use Core\Database\Migration\Migration;

class AddMfaCodeToUsersTable extends Migration
{
    public function up(): void
    {
        $this->execute('ALTER TABLE users ADD COLUMN mfa_code VARCHAR(6) NULL');
        $this->execute('ALTER TABLE users ADD COLUMN mfa_code_expires_at TIMESTAMP NULL');
    }

    public function down(): void
    {
        $this->execute('ALTER TABLE users DROP COLUMN mfa_code');
        $this->execute('ALTER TABLE users DROP COLUMN mfa_code_expires_at');
    }
}
