<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');   // Client relation
            $table->unsignedBigInteger('invoice_id')->nullable(); // Invoice relation
            $table->decimal('amount', 10, 2);
            $table->decimal('due', 10, 2)->default(0);
            $table->enum('payment_method', ['cash', 'bkash', 'nagad', 'bank', 'other'])->default('cash');
            $table->date('paid_date');
            $table->string('transaction_id')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
