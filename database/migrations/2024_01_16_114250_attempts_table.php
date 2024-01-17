<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('attempts', function (Blueprint $table) {
            $table->string('time');
            
            $table->bigInteger('match_id')->unsigned()->index()->nullable();
            $table->foreign('match_id')->references('id')->on('match')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
