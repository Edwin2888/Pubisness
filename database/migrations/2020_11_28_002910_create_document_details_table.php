<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('document_details')){
            Schema::disableForeignKeyConstraints();
            Schema::create('document_details', function (Blueprint $table) {
                $table->bigIncrements('id_auto');
                $table->integer('id_document');
                $table->integer('id_product');
                $table->decimal('quantity', 12, 2);
                $table->decimal('price', 12, 2);
                $table->bigInteger('id_user');
                $table->string('observation')->nullable();
                $table->timestamps();
                $table->softDeletes();

                // $table->foreign('id_user')->references('id')->on('users');
                // $table->foreign('id_document')->references('id_document')->on('documents');
                // $table->foreign('id_product')->references('id_product')->on('products');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('document_details');
        Schema::enableForeignKeyConstraints();
    }
}
