<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddWidthInchesToCardSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('card_settings', function(Blueprint $table)
		{
			$table->decimal('width_inches');
			$table->decimal('height_inches');
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
			$table->dropColumn('width_inches');
			$table->dropColumn('height_inches');
		});
	}

}
