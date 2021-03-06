<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills_to_pay', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('type');
            $table->string('description')->nullable();
            $table->date('due_date');
            $table->double('cost');
            $table->double('paid_cost')->nullable();
            $table->date('paid_date')->nullable();
            $table->double('delay_cost')->nullable();
            $table->unsignedInteger('category_id');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('bills_to_pay_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bills_to_pay');
    }
};
