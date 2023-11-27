<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTypeColumnInMapsPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maps_positions', function (Blueprint $table) {
            $table_name = $table->getTable();
            DB::statement("ALTER TABLE $table_name MODIFY COLUMN type ENUM('private', 'public', 'coach') not null default 'public'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maps_positions', function (Blueprint $table) {
            $table_name = $table->getTable();
            DB::statement("ALTER TABLE $table_name MODIFY COLUMN type ENUM('private', 'public') not null default 'public'");
        });
    }
}
