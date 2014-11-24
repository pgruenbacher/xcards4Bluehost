<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddMessageWidthToCardSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('card_settings', function(Blueprint $table)
		{
			$table->integer('message_width');
			$table->integer('message_height');
			$table->integer('message_left');
			$table->integer('message_top');
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
			$table->dropColumn('message_width');
			$table->dropColumn('message_height');
			$table->dropColumn('message_left');
			$table->dropColumn('message_top');
		});
	}

}
