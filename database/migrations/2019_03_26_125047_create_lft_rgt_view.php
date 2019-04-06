<?php

use Illuminate\Database\Migrations\Migration;

class CreateLftRgtView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('DROP VIEW IF EXISTS vw_lftrgt');
        DB::unprepared(file_get_contents(base_path('database/sql/traversal_view.sql')));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS vw_lftrgt;');
    }
}
