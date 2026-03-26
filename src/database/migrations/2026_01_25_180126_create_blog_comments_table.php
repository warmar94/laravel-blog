<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_articles_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('reply_to')->nullable();
            $table->text('comment');
            $table->timestamps();

            $table->index('post_id');
            $table->index('user_id');
            $table->index('reply_to');

            $table->foreign('post_id')
                ->references('id')
                ->on('data_articles')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('reply_to')
                ->references('id')
                ->on('data_articles_comments')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_articles_comments');
    }
};
