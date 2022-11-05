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
        Schema::create('items', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->uuid("stock_id");
            $table->uuid("kind_id");
            $table->uuid("merk_id");
            $table->uuid("unit_id");
            $table->string("name");
            $table->string("code")->unique()->nullable();
            $table->tinyInteger("status")->default(1); // 1: tersedia, 0: tidak tersedia
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
};
