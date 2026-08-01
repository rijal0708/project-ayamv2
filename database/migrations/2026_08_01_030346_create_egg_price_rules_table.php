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
        Schema::create('egg_price_rules', function (Blueprint $table) {
            $table->id();
	    $table->foreignId('buyer_id')->nullable()->constrained('buyers')->nullOnDelete();
	    $table->enum('grade', ['A', 'B', 'C', 'Jumbo', 'cracked']);
	    $table->string('unit', 10)->default('butir');
	    $table->decimal('price', 10, 2);
	    $table->date('effective_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egg_price_rules');
    }
};
