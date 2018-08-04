<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->string('imagem');
            $table->integer('categoria_produto_id')->unsigned();
            $table->foreign('categoria_produto_id')->references('id')->on('categories');
            $table->timestamps();
        });

        Schema::create('m_product_aluminum', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('m_produto_id')->unsigned();
            $table->integer('aluminio_id')->unsigned();
            $table->foreign('m_produto_id')->references('id')->on('m_products')->onDelete('cascade');
            $table->foreign('aluminio_id')->references('id')->on('aluminums')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('m_product_component', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('m_produto_id')->unsigned();
            $table->integer('componente_id')->unsigned();
            $table->foreign('m_produto_id')->references('id')->on('m_products')->onDelete('cascade');
            $table->foreign('componente_id')->references('id')->on('components')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('m_product_glass', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('m_produto_id')->unsigned();
            $table->integer('vidro_id')->unsigned();
            $table->foreign('m_produto_id')->references('id')->on('m_products')->onDelete('cascade');
            $table->foreign('vidro_id')->references('id')->on('glasses')->onDelete('cascade');
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
        Schema::dropIfExists('m_products');
        Schema::dropIfExists('m_product_aluminum');
        Schema::dropIfExists('m_product_component');
        Schema::dropIfExists('m_product_glass');
    }
}
