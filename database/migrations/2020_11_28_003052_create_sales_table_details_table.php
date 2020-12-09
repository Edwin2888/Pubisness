<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTableDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('sales_table_details')){
            Schema::disableForeignKeyConstraints();
            Schema::create('sales_table_details', function (Blueprint $table) {
                $table->bigIncrements('id_auto');
                $table->integer('id_sale');
                $table->integer('id_product');
                $table->decimal('quantity', 12, 2);
                $table->decimal('price', 12, 2);
                $table->integer('id_user');
                $table->integer('state');
                $table->string('observation')->nullable();
                $table->timestamps();
                $table->softDeletes();

                // $table->foreign('id_user')->references('id')->on('users');
                // $table->foreign('id_sale')->references('id_sale')->on('sales_tables');
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
        Schema::dropIfExists('sales_table_details');
        Schema::enableForeignKeyConstraints();
    }
}
