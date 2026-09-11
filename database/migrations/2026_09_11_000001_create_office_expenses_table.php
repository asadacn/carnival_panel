<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeExpensesTable extends Migration
{
    public function up()
    {
        Schema::create('office_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('expense_date')->index();
            $table->string('category')->index();
            $table->string('title', 120);
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('payment_method', 30)->index();
            $table->string('reference_number', 100)->nullable()->index();
            $table->text('notes')->nullable();
            $table->string('receipt_path', 255)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('office_expenses');
    }
}
