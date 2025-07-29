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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number')->unique();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->enum('status', ['pending', 'approved', 'received', 'processed', 'cancelled'])->default('pending');
            $table->enum('type', ['return', 'exchange', 'refund'])->default('return');
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('refund_method')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
