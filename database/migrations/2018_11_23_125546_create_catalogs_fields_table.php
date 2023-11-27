<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatalogsFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catalogs_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->index();
            $table->enum('type', \App\Librerias\SpecialText\LibSpecialTextCatalogs::FIELD_TYPES);
            $table->unsignedInteger('order')->default(0)->index('order');
            $table->enum('status', ['active', 'inactive'])->default('active')->index('status');
            $table->longText('help_text')->nullable();
            $table->string('validation')->nullable();
            $table->string('default_value')->nullable();
            $table->boolean('can_repeat')->default(false);
            $table->boolean('hidden_in_list')->default(true)->index('hidden_in_list');
            $table->boolean('sortable')->default(false)->index('sortable');

            $table->unsignedInteger('catalogs_id')->index('catalogs_id');
            $table->unsignedInteger('catalogs_groups_id')->index('catalogs_groups_id');
            $table->unsignedInteger('companies_id')->index('companies_id');

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
        Schema::dropIfExists('catalogs_fields');
    }
}
