<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAddressCardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('addresses_cards', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('addresses_id')->unsigned()->index();
			$table->foreign('addresses_id')->references('id')->on('addresses')->onDelete('cascade');
			$table->integer('cards_id')->unsigned()->index();
			$table->foreign('cards_id')->references('id')->on('cards')->onDelete('cascade');
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
		Schema::drop('addresses_cards');
	}

}
