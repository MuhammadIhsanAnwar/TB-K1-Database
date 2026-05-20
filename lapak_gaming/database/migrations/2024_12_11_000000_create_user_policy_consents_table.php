<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_policy_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('policy_type', ['terms_of_service', 'privacy_policy', 'data_processing']);
            $table->string('version')->default('1.0');
            $table->timestamp('agreed_at')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->enum('consent_status', ['agreed', 'declined', 'pending'])->default('pending');
            $table->timestamps();

            $table->index(['user_id', 'policy_type']);
            $table->unique(['user_id', 'policy_type', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_policy_consents');
    }
};
