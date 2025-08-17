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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('publisher');
            $table->integer('page_count');
            $table->integer('unit_count');
            $table->string('isbn');
            $table->string('language');
            $table->enum('binding', ['hardcover', 'paperback', 'spiral_bound']);
            $table->enum('script', ['latin', 'cyrillic', 'arabic']);
            $table->enum('dimensions', ['A1', 'A2', '21cm x 29.7cm', '15cm x 21cm']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
