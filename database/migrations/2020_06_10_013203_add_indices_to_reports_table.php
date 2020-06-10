<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndicesToReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->foreign('reporter_id')->references('id')->on('users');
            $table->foreign('target_id')->references('id')->on('users');

            $table->index('reporter_id');
            $table->index('target_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign('reporter_id');
            $table->dropForeign('target_id');

            $table->dropIndex('reporter_id');
            $table->dropIndex('target_id');
        });
    }
}
