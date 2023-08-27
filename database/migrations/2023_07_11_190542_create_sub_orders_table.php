<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->references("id")->on("orders")->onDelete("cascade");
            $table->double("phone")->nullable();
            $table->text("pieces");
            $table->date("start_date");
            $table->date("predicted_date")->nullable();
            $table->date("end_date")->nullable();
            $table->integer("advance")->default(0);
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
        Schema::dropIfExists('sub_orders');
    }
}
