<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleTablePaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('sale_table_pays')){
            Schema::create('sale_table_pays', function (Blueprint $table) {
                $table->bigIncrements('id_pay');
                $table->integer('id_sale');
                $table->decimal('payment', 12, 2);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_table_pays');
    }
}
