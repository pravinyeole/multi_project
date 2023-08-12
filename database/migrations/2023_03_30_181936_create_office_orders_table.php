<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficeOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_orders', function (Blueprint $table) {
            $table->bigIncrements('office_order_id');
            $table->string('ref_no')->nullable();
            $table->string('exam_year')->nullable();
            $table->string('examtype')->nullable();
            $table->date('submission_date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('office_orders');
    }
}
