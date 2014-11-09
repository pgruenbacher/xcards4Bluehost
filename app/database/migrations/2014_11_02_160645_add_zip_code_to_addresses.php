<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddZipCodeToAddresses extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('addresses', function(Blueprint $table)
		{
			$table->integer('zip_code');
			$table->string('city_name');
			$table->string('state_abbreviation');
			$table->string('street_name');
			$table->bigInteger('primary_number');
			$table->string('delivery_line_1');
			$table->string('last_line');
			$table->string('carrier_route');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('addresses', function(Blueprint $table)
		{
			$table->dropColumn('zip_code');
			$table->dropColumn('city_name');
			$table->dropColumn('state_abbreviation');
			$table->dropColumn('street_name');
			$table->dropColumn('primary_number');
			$table->dropColumn('delivery_line_1');
			$table->dropColumn('last_line');
			$table->dropColumn('carrier_route');
		});
	}

}
