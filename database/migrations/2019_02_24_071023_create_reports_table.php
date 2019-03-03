<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
			$table->string('reason');

			$table->string('reporter_name');
			$table->string('reporter_steam_id');

			$table->string('target_name');
			$table->string('target_steam_id');

			$table->integer('reporter_id')->unsigned()->references('id')->on('users');
			$table->integer('target_id')->unsigned()->references('id')->on('users');

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
