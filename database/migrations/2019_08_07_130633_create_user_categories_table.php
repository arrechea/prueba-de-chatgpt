<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * @throws \Exception
     */
    public function up(): void
    {
        Schema::create(
            'user_categories',
            static function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->integer('companies_id')->unsigned();
                $table->longText('description')->nullable();

                $table->unique(['name', 'companies_id'], 'user_categories_uniq');

                $table->foreign('companies_id')
                    ->references('id')
                    ->on('companies')
                    ->onDelete('cascade');

                $table->timestamps();
                $table->softDeletes();
            }
        );

        Schema::create(
            'user_category_user_profile',
            static function (Blueprint $table) {
                $table->integer('profile_id')->unsigned();
                $table->integer('category_id')->unsigned();

                $table->foreign('profile_id')
                    ->references('id')
                    ->on('user_profiles');

                $table->foreign('category_id')
                    ->references('id')
                    ->on('user_categories');

                $table->primary(['profile_id', 'category_id'], 'user_category_user_profile_pk');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('user_category_user_profile');
        Schema::dropIfExists('user_categories');
    }
}
