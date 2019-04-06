<?php

use Illuminate\Database\Migrations\Migration;

class AddTreeTraversalProcedure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS tree_traversal');
        DB::unprepared(file_get_contents(base_path('database/sql/traversal_procedure.sql')));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS tree_traversal;');
    }
}
