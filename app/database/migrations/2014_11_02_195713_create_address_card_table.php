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
		Schema::create('address_card', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('address_id')->unsigned()->index();
			$table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');
			$table->integer('card_id')->unsigned()->index();
			$table->foreign('card_id')->references('id')->on('cards')->onDelete('cascade');
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
		Schema::drop('address_card');
	}

}
