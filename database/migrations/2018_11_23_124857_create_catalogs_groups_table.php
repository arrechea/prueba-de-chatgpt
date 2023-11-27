<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogsGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogs_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->index();
            $table->unsignedInteger('order')->default(0)->index('order');
            $table->boolean('can_repeat')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active')->index('status');
            $table->longText('description')->nullable();
            $table->unsignedInteger('catalogs_id')->index('catalogs_id');
            $table->unsignedInteger('companies_id')->index('companies_id');
            $table->unsignedInteger('brands_id')->index('brands_id')->nullable();

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
        Schema::dropIfExists('catalogs_groups');
    }
}
