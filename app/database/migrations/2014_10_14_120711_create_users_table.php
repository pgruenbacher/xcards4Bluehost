<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->tinyInteger('guest');
			$table->string('billing_id');
			$table->integer('credits');
			$table->string('first');
			$table->string('last');
			$table->string('email');
			$table->integer('phone_number');
			$table->string('password');
			$table->string('password_temp');
			$table->string('regIP');
			$table->tinyInteger('active');
			$table->string('code');
			$table->string('url_temp');
			$table->string('remember_token');
			$table->string('instagram_token');
			$table->string('facebook_token');
			$table->integer('instagram_id');
			$table->integer('facebook_id');
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
		Schema::drop('users');
	}

}
