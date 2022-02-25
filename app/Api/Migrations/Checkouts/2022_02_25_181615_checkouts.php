<?php

use App\Api\Models\CheckoutModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Checkouts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->integer('user_id')->unsigned()->index();
            $table->float('amount', 11, 2);
            $table->string('reference', 255)->unique();
            $table->enum('status',CheckoutModel::$statuses)->default(CheckoutModel::STATUS_PENDING);
            $table->dateTime('completed_at')->nullable();
            $table->float('refunded', 11, 2)->nullable();
            $table->dateTime('refunded_at')->nullable();
            $table->string('checkout_id')->index();
            $table->json('response');
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
        Schema::dropIfExists('checkouts');
    }
}
