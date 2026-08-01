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
        Schema::create('egg_sales', function (Blueprint $table) {
            $table->id();
	    $table->string('invoice_no', 30)->unique();
	    $table->date('date');
	    $table->foreignId('buyer_id')->constrained('buyers');
        $table->enum('payment_method', ['cash', 'transfer', 'credit'])->nullable();
        $table->date('due_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egg_sales');
    }
};
