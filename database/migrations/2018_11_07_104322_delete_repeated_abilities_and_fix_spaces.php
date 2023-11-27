<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteRepeatedAbilitiesAndFixSpaces extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $ids = [25, 27];
        DB::table('abilities')->update([
            'name' => DB::raw('trim(name)'),
        ]);
        DB::table('permissions')->where('ability_id', 27)->update([
            'ability_id' => 185,
        ]);
        DB::table('permissions')->where('ability_id', 25)->update([
            'ability_id' => 201,
        ]);
        DB::table('abilities')->whereIn('id', $ids)->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
