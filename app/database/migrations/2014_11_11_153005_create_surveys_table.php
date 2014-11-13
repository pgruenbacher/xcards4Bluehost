<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSurveysTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('surveys', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('trips');
			$table->boolean('special');
			$table->longtext('comments');
			$table->boolean('holiday');
			$table->boolean('frequently');
			$table->boolean('infrequently');
			$table->boolean('invitations');
			$table->decimal('price',2);
			$table->integer('rating');
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
		Schema::drop('surveys');
	}

}
