<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCreditRateToCardSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('card_settings', function(Blueprint $table)
		{
			$table->decimal('credit_rate');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('card_settings', function(Blueprint $table)
		{
			$table->dropColumn('credit_rate');
		});
	}

}
