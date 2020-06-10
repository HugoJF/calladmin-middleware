<?php

use App\Vote;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndicesToVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('votes', function (Blueprint $table) {
            Vote::join('reports', 'report_id', '=', 'reports.id', 'left')->whereNull('reports.id')->delete();
            Vote::join('users', 'user_id', '=', 'users.id', 'left')->whereNull('users.id')->delete();

            $table->foreign('report_id')->references('id')->on('reports');
            $table->foreign('user_id')->references('id')->on('users');

            $table->index('report_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropForeign('report_id');
            $table->dropForeign('user_id');

            $table->dropIndex('report_id');
            $table->dropIndex('user_id');
        });
    }
}
