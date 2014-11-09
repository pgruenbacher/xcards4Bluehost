<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemoveUserIdFromRolesUser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('roles_user', function(Blueprint $table)
		{
			$table->dropColumn('user_id');
			$table->bigInteger('users_id');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('roles_user', function(Blueprint $table)
		{
			$table->bigInteger('user_id');
			$table->dropColumn('users_id');
		});
	}

}
