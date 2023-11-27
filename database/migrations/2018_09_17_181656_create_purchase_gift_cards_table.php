<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseGiftCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_gift_cards', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code')->index();
            $table->boolean('is_redempted')->index()->default(false);
            $table->boolean('is_active')->index()->default(false);

            $table->unsignedInteger('purchases_id')->index();
            $table->unsignedInteger('redempted_by_user_profiles_id')->nullable()->index();
            $table->unsignedInteger('redempted_by_admin_profiles_id')->nullable()->index();

            $table->timestamp('redempted_at')->nullable();

            $table->unsignedInteger('locations_id')->index()->nullable();
            $table->unsignedInteger('brands_id')->index()->nullable();
            $table->unsignedInteger('companies_id')->index()->nullable();

            $table->timestamps();
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->boolean('is_gift_card')->after('status')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_gift_cards');

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('is_gift_card');
        });
    }
}
