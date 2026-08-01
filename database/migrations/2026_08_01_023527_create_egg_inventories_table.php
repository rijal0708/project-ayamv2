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
        Schema::create('egg_inventories', function (Blueprint $table) {
            $table->id();
	    $table->enum('grade', ['A', 'B', 'C', 'Jumbo', 'cracked']);
	    $table->date('date');
	    $table->integer('qty_in')->default(0);
	    $table->integer('qty_out')->default(0);
	    $table->integer('balance')->default(0);
	    $table->enum('source', ['production', 'purchase', 'return']);
	    $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();

	    $table->unique(['grade', 'date', 'source', 'reference_id'], 'egg_inventory_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egg_inventories');
    }
};
