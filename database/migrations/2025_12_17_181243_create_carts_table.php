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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->nullable()->references('id')->on('admins')->nullOnDelete(); // onsite
            $table->foreignId('user_id')->nullable()->references('id')->on('users')->nullOnDelete(); // online
            $table->uuid('cookie_id')->nullable();   // guest
            $table->foreignId('meal_id')->references('id')->on('meals')->onDelete('cascade');
            $table->unsignedSmallInteger('quantity')->default(1);
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
        Schema::dropIfExists('carts');
    }
};
