<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->index();

            $table->unsignedInteger('locations_id')->nullable()->index();
            $table->unsignedInteger('brands_id')->nullable()->index();
            $table->unsignedInteger('companies_id')->nullable()->index();

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('short_description')->nullable();
            $table->longText('terms')->nullable();

            $table->enum('discount_type', ['price', 'percent'])->default('price');
            $table->unsignedInteger('discount_number');
            $table->dateTime('discount_from')->nullable()->index();
            $table->dateTime('discount_to')->nullable()->index();

            $table->unsignedInteger('total_uses')->nullable()->index();
            $table->unsignedInteger('users_uses')->default(1)->index();

            $table->integer('purchases_min')->nullable();
            $table->integer('purchases_max')->nullable();

            $table->string('pic')->nullable();
            $table->boolean('is_public')->default(false)->index();
            $table->boolean('in_home')->default(false)->index();
            $table->boolean('is_valid_for_all')->default(true)->index();
            $table->boolean('is_valid_for_combos')->default(false)->index();
            $table->boolean('is_valid_for_memberships')->default(false)->index();

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
        Schema::dropIfExists('discount_codes');
    }
}
