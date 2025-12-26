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
         Schema::create('students', function (Blueprint $table) {
            $table->id(); // PK auto
            $table->string('sl_no')->unique(); // Auto generated
            $table->string('reg_no')->unique();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('phone',20);
            $table->string('qualification')->nullable();
            $table->date('admission_date');
            $table->foreignId('course_id')->constrained()->cascadeOnUpdate();
            $table->foreignId('scheme_id')->constrained()->cascadeOnUpdate();
            $table->decimal('total_fees',10,2);
            $table->tinyInteger('status')->default(1);
            $table->softDeletes();
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
        Schema::dropIfExists('students');
    }
};
