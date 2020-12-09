<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('documents')){
            Schema::disableForeignKeyConstraints();
            Schema::create('documents', function (Blueprint $table) {
                $table->bigIncrements('id_document');
                $table->string('code')->nullable();
                $table->bigInteger('id_user');
                $table->integer('id_type');
                $table->string('descrition')->nullable();
                $table->decimal('total', 12, 2);
                $table->decimal('payment', 12, 2);
                $table->timestamps();
                $table->softDeletes();

                // $table->foreign('id_user')->references('id')->on('users');
                // $table->foreign('id_type')->references('id_type')->on('type_documents');
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
        Schema::dropIfExists('documents');
        Schema::enableForeignKeyConstraints();
    }
}
