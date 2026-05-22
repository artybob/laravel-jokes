<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jokes', function (Blueprint $table) {
            $table->id();
            $table->integer('api_id')->nullable();
            $table->string('type', 50);
            $table->text('setup')->nullable();
            $table->text('punchline')->nullable();
            $table->text('joke')->nullable();
            $table->json('raw_data');
            $table->timestamps();

            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('jokes');
    }
};
