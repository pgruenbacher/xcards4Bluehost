<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCardSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('card_settings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->integer('image_id');
			$table->decimal(' credit_rate');
			$table->decimal('dollar_rate');
			$table->integer('width');
			$table->integer('height');
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
		Schema::drop('card_settings');
	}

}
