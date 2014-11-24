<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddFrontDrawingClassToCardSettings extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('card_settings', function(Blueprint $table)
		{
			$table->string('front_drawing_class');
			$table->string('back_drawing_class');
			$table->string('orientation');
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
			$table->dropColumn('front_drawing_class');
			$table->dropColumn('back_drawing_class');
			$table->dropColumn('orientation');
		});
	}

}
