<?php

use App\Api\Models\AccountTypeModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_types', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->text('type', 10);
            $table->timestamps();
        });

        foreach(AccountTypeModel::$types as $type) {
            $accountType = new AccountTypeModel(['type' => $type]);
            $accountType->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_types');
    }
}
