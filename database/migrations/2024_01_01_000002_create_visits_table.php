<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id', 100);
            $table->string('ip', 45);
            $table->string('city', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('device_type', 50);
            $table->string('browser', 50);
            $table->string('os', 50);
            $table->string('page_url')->nullable();
            $table->string('referrer')->nullable();
            $table->timestamps();
            
            $table->index(['visitor_id', 'created_at']);
            $table->index('city');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('visits');
    }
};
