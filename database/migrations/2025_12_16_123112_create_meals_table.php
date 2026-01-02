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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->string('title') ;
            $table->text('description') ;
            $table->decimal('price') ;
            $table->decimal('compare_price')->nullable();
            $table->string('image') ;
            $table->enum('status' , ['active' , 'inactive']);
            $table->integer('preparation_time') ;
            $table->foreignId('category_id')->references('id')->on('categories')->onDelete('cascade') ;
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
        Schema::dropIfExists('meals');
    }
};
