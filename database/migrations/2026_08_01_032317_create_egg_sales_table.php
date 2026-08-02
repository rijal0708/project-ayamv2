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
        $table->decimal('subtotal', 12, 2)->default(0);
        $table->decimal('discount', 12, 2)->default(0);
        $table->decimal('grand_total', 12, 2)->default(0);
        $table->decimal('paid_amount', 12, 2)->default(0);
        $table->enum('payment_status', ['paid', 'partial', 'unpaid', 'overdue'])->default('unpaid');
        $table->enum('status', ['draft', 'confirmed', 'shipped', 'completed', 'cancelled'])->default('draft');
        $table->text('notes')->nullable();
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
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
