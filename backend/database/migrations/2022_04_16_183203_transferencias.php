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
        Schema::create('transferencias_bancarias', function (Blueprint $table) 
        {
            $table->increments('id');
            $table->timestamps();
            $table->string('banco_origem');
            $table->string('agencia_origem');
            $table->string('conta_origem');
            $table->string('banco_destino');
            $table->string('agencia_destino');
            $table->string('conta_destino');
            $table->string('valor_transferido');
            $table->unsignedInteger('csv_id')->nullable();
            $table->foreign('csv_id')->references('id')->on('csv_uploads')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
