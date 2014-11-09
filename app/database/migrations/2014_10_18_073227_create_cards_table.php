<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cards', function(Blueprint $table)
		{
			$table->increments('id');
			$table->bigInteger('user_id');
			$table->bigInteger('original_image');
			$table->bigInteger('cropped_image');
			$table->bigInteger('finished_at');
			$table->bigInteger('back_image');
			$table->longText('back_message');
			$table->longText('front_message');
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
		Schema::drop('cards');
	}

}
