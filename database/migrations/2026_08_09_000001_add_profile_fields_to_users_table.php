<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('roll')->nullable()->after('phone');
            $table->string('role')->default('user')->after('roll');
            $table->string('contact_info')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('contact_info');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'roll', 'role', 'contact_info', 'is_active']);
        });
    }
};
