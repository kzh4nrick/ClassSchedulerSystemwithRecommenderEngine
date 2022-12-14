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
        Schema::create('curricula', function (Blueprint $table) {
            $table->id();
            $table->string('academicYear', 20);
            $table->string('semester', 30);
            $table->foreignId('course_id')->constrained();
            $table->string('yearLevel', 50);
            $table->foreignId('subject_id')->constrained();
            $table->tinyInteger('Units');
            $table->tinyInteger('lec');
            $table->tinyInteger('lab');
            $table->string('faculties_id', 100);
            $table->string('classrooms_id', 100);
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('curricula');
    }
};
