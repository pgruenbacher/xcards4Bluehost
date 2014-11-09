<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateImagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('images', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('filename');
			$table->string('path');
			$table->string('file_path');
			$table->bigInteger('size');
			$table->bigInteger('width');
			$table->bigInteger('height');
			$table->string('extension');
			$table->string('mimetype');
			$table->bigInteger('user_id');
			$table->bigInteger('cards_id');
			$table->bigInteger('parent_id');
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
		Schema::drop('images');
	}

}
