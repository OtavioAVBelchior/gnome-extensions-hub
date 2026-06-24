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
        Schema::create('cached_merge_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extension_id')->constrained()->cascadeOnDelete();
            $table->string('platform');
            $table->string('mr_iid')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('state');
            $table->string('author');
            $table->json('labels');
            $table->timestamp('opened_at');
            $table->timestamp('last_updated_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cached_merge_requests');
    }
};
