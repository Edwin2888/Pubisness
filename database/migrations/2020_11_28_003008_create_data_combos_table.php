<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDataCombosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('data_combos')){
            Schema::disableForeignKeyConstraints();
            Schema::create('data_combos', function (Blueprint $table) {
                $table->bigIncrements('id_combo');
                $table->bigInteger('id_product');
                $table->decimal('price', 12, 2);
                $table->integer('name');
                $table->string('observation')->nullable();
                $table->timestamps();
                $table->softDeletes();

                // $table->foreign('id_product')->references('id_product')->on('products');
            });
            Schema::enableForeignKeyConstraints();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('data_combos');
        Schema::enableForeignKeyConstraints();
    }
}
