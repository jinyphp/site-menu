<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('menu_id')->default(0);
            $table->string('enable')->nullable();

            $table->string('icon')->nullable();
            $table->string('title');

            $table->string('href')->nullable();
            $table->string('target')->nullable();
            $table->string('selected')->nullable();

            $table->string('submenu')->nullable();
            $table->integer('ref')->default(0);
            $table->integer('level')->default(0);
            $table->integer('pos')->default(1);

            $table->string('description')->nullable();


            // 작업자ID
            $table->unsignedBigInteger('user_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');

    }
}
