<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('person_id')->constrained('persons')->cascadeOnDelete();
            $table->string('title');
            $table->text('details');
            $table->boolean('is_anonymous')->default(false);
            $table->string('status')->default('pending');
            $table->boolean('assigned_to_lawyers')->default(false);
            $table->boolean('assigned_to_counselors')->default(false);
            $table->boolean('assigned_to_law_enforcers')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
