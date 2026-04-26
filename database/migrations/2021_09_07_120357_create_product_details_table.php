<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('product_id');
            $table->text('description')->nullable();
            $table->string('lang1')->nullable();
            $table->string('lang2')->nullable();
            $table->string('lang3')->nullable();
            $table->boolean('alerg_apio')->default(false);
            $table->boolean('alerg_crustaceans')->default(false);
            $table->boolean('alerg_dairy')->default(false);
            $table->boolean('alerg_sulphites')->default(false);
            $table->boolean('alerg_egg')->default(false);
            $table->boolean('alerg_gluten')->default(false);
            $table->boolean('alerg_lupins')->default(false);
            $table->boolean('alerg_mollusks')->default(false);
            $table->boolean('alerg_mustard')->default(false);
            $table->boolean('alerg_peanuts')->default(false);
            $table->boolean('alerg_peelfruits')->default(false);
            $table->boolean('alerg_sesame')->default(false);
            $table->boolean('alerg_soy')->default(false);
            $table->boolean('alerg_fish')->default(false);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_details');
    }
}
