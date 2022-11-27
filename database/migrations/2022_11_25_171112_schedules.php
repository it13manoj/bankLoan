<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Schedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_detail_id');
            $table->unsignedBigInteger('loan_id');
            $table->decimal('emi', $precision = 50, $scale = 2);
            $table->decimal('interest', $precision = 50, $scale = 2)->nullable();
            $table->date('pay_date')->nullable();
            $table->enum("status",[0,1]);
            $table->timestamps();
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            $table->foreign('loan_detail_id')->references('id')->on('loan_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
