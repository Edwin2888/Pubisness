<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('sales_tables')){
            Schema::disableForeignKeyConstraints();
            Schema::create('sales_tables', function (Blueprint $table) {
                $table->bigIncrements('id_sale');
                $table->string('name');
                $table->date('sale_date');
                $table->string('descrition')->nullable();
                $table->bigInteger('id_user');
                $table->bigInteger('status');
                $table->decimal('total', 12, 2);
                $table->timestamps();
                $table->softDeletes();

                // $table->foreign('id_user')->references('id')->on('users');
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
        Schema::dropIfExists('sales_tables');
        Schema::enableForeignKeyConstraints();
    }
}
