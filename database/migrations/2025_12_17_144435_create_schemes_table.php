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
       Schema::create('schemes', function (Blueprint $table) {
            $table->id(); // auto increment primary key
            $table->string('name');
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->softDeletes(); // deleted_at
            $table->timestamps();  // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schemes');
    }
};
