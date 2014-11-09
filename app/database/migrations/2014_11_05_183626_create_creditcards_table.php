<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCreditcardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('creditcards', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('stripe_id');
			$table->integer('last4');
			$table->string('brand');
			$table->integer('exp_month');
			$table->integer('exp_year');
			$table->string('fingerprint');
			$table->string('country');
			$table->string('customer_id');
			$table->string('type');
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
		Schema::drop('creditcards');
	}

}
