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
        Schema::create('rates', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_id'); // not null
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->unsignedBigInteger('product_id'); // not null
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->unique(['customer_id', 'product_id']); // no se puede repetir este par

            $table->enum('star', [0, 1, 2, 3, 4, 5])->default(0); // not null

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
        Schema::dropIfExists('rates');
    }
};
