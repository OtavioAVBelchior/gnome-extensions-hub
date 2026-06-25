<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('extensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('platform'); // github | gitlab
            $table->string('repo_full_name'); // ex: GNOME/gnome-shell-extensions
            $table->string('extension_name');
            $table->string('uuid')->unique();
            $table->json('metadata')->nullable();
            $table->json('supported_versions');
            $table->string('current_version')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

            $table->unique(['platform', 'repo_full_name', 'user_id']);
        });
    }
};