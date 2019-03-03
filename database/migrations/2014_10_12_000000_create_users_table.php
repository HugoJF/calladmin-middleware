<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->nullable();
			$table->string('avatar')->nullable();
			$table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
			$table->string('steamid');
			$table->boolean('banned')->default(false);
			$table->boolean('admin')->default(false);
			$table->boolean('ignore_reports')->default(false);
			$table->boolean('ignore_targets')->default(false);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
