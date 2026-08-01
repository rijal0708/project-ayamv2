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
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
	    $table->string('name',200);
	    $table->enum('type', ['collector', 'market', 'retail', 'individual'])->nullable();
	    $table->string('phone', 20)->nullable();
	    $table->text('addres')->nullable();
	    $table->decimal('credit_limit', 12, 2)->default(0);
	    $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buyers');
    }
};
