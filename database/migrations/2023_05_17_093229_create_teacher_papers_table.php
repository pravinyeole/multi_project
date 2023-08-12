<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeacherPapersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_paper', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('department_id')->nullable();
            $table->string('class_id')->nullable();
            $table->integer('subject_id')->nullable();
            $table->date('exam_date')->nullable();
            $table->string('pattern')->nullable();
            $table->string('chairman')->nullable();
            $table->string('email')->nullable();
            $table->string('internal_paper_satter')->nullable();
            $table->string('external_paper_satter')->nullable();
            $table->string('paper1')->nullable();
            $table->string('paper2')->nullable();
            $table->string('paper3')->nullable();
            $table->string('paper1_cost')->nullable();
            $table->string('paper2_cost')->nullable();
            $table->string('paper3_cost')->nullable();
            $table->integer('total')->nullable();
            $table->string('quantity1')->nullable();
            $table->string('quantity2')->nullable();
            $table->string('quantity3')->nullable();
            $table->date('date')->nullable();
            $table->string('selected_paper')->nullable();
            $table->date('selected_p_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_papers');
    }
}
