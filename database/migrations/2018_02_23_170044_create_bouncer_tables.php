<?php

use Silber\Bouncer\Database\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBouncerTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Models::table('abilities'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('title')->nullable();
            $table->integer('entity_id')->unsigned()->nullable()->index('entity_id');
            $table->string('entity_type', 150)->nullable();
            $table->boolean('only_owned')->default(false);
            $table->integer('scope')->nullable()->index();
            $table->timestamps();
        });

        Schema::create(Models::table('roles'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150);
            $table->string('title')->nullable();
            $table->integer('level')->unsigned()->nullable();
            $table->integer('scope')->nullable()->index();
            $table->nullableMorphs('owner');
            $table->integer('companies_id')->unsigned()->nullable()->index('companies_id');
            $table->integer('brands_id')->unsigned()->nullable()->index('brands_id');
            $table->integer('locations_id')->unsigned()->nullable()->index('locations_id');
            $table->enum('type', [
                \App\Librerias\Permissions\LibPermissions::LEVEL_GAFAFIT,
                \App\Librerias\Permissions\LibPermissions::LEVEL_COMPANY,
                \App\Librerias\Permissions\LibPermissions::LEVEL_BRAND,
                \App\Librerias\Permissions\LibPermissions::LEVEL_LOCATION,
            ]);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['name', 'scope'],
                'roles_name_unique'
            );
        });

        Schema::create(Models::table('assigned_roles'), function (Blueprint $table) {
            $table->integer('role_id')->unsigned()->index();
            $table->integer('entity_id')->unsigned()->index();
            $table->string('entity_type', 150);
            $table->integer('scope')->nullable()->index();
            $table->nullableMorphs('assigned');

            $table->index(
                ['entity_id', 'entity_type', 'scope'],
                'assigned_roles_entity_index'
            );

            $table->foreign('role_id')
                ->references('id')->on(Models::table('roles'))
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create(Models::table('permissions'), function (Blueprint $table) {
            $table->integer('ability_id')->unsigned()->index();
            $table->integer('entity_id')->unsigned()->index();
            $table->string('entity_type', 150);
            $table->boolean('forbidden')->default(false);
            $table->integer('scope')->nullable()->index();

            $table->index(
                ['entity_id','entity_type', 'scope'],
                'permissions_entity_index'
            );

            $table->foreign('ability_id')
                ->references('id')->on(Models::table('abilities'))
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(Models::table('permissions'));
        Schema::drop(Models::table('assigned_roles'));
        Schema::drop(Models::table('roles'));
        Schema::drop(Models::table('abilities'));
    }
}
