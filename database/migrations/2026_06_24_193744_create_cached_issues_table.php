<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cached_issues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extension_id')->constrained()->cascadeOnDelete();
            $table->string('platform');
            $table->string('issue_iid')->index();
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
};