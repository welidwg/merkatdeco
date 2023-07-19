<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("client");
            $table->foreignId("governorate_id")->references("id")->on("governorates")->onDelete("cascade");
            $table->string("address");
            $table->string("source");
            $table->integer("phone");
            $table->json("products");
            $table->text("details")->nullable();
            $table->date("order_date");
            $table->date("delivery_date")->nullable();
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
        Schema::dropIfExists('orders');
    }
}
