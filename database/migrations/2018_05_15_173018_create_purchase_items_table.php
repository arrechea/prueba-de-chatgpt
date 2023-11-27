<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('purchases_id')->index();
            $table->morphs('buyed');
            $table->float('quantity')->default(1);
            $table->boolean('assigned')->default(false);

            $table->unsignedInteger('locations_id')->index();
            $table->unsignedInteger('brands_id')->index();
            $table->unsignedInteger('companies_id')->index();

            $table->unsignedInteger('admin_profiles_id')->nullable()->index();
            $table->unsignedInteger('user_profiles_id')->index();
            $table->unsignedInteger('users_id')->index();

            $table->string('item_name');
            $table->float('item_discount')->default(0);
            $table->float('item_price')->default(0);
            $table->float('item_price_final')->default(0);
            $table->float('item_credits')->default(0);
            $table->unsignedInteger('credits_id')->index()->nullable();

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
        Schema::dropIfExists('purchase_items');
    }
}
