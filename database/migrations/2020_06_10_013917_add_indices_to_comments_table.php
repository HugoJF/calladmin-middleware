<?php

use App\Comment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndicesToCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comments', function (Blueprint $table) {
            Comment::join('reports', 'report_id', '=', 'reports.id', 'left')->whereNull('reports.id')->forceDelete();

            $table->foreign('report_id')->references('id')->on('reports');

            $table->index('report_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign('report_id');

            $table->dropIndex('report_id');
        });
    }
}
