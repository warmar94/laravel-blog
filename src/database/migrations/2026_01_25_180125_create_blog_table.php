<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_articles', function (Blueprint $table) {
            $table->id();
            $table->text('metatitle');
            $table->text('metadesc');
            $table->text('metakeywords');
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('article'); // JSON content
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('published_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_articles');
    }
};
