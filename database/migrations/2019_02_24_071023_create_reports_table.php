<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('id');

            $table->string('server_ip');
            $table->string('server_port');
            $table->string('reason');
            $table->boolean('vip')->default(false);

            $table->string('reporter_name');
            $table->string('reporter_steam_id');

            $table->string('target_name');
            $table->string('target_steam_id');

            $table->integer('reporter_id')->unsigned();
            $table->integer('target_id')->unsigned();

            $table->boolean('decision')->nullable();

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
        Schema::dropIfExists('reports');
    }
}
