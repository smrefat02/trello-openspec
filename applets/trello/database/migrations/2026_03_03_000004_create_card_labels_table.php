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
        Schema::create('card_labels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('cards')->onDelete('cascade');
            $table->string('name', 100);
            $table->enum('color', ['purple', 'green', 'yellow', 'orange', 'red', 'blue', 'pink', 'gray']);
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();
            
            $table->index(['card_id', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('card_labels');
    }
};
