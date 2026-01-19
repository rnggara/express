<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateUserMcuTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_mcu', function(Blueprint $table) {
			$table->increments('id');
			$table->integer("user_id")->nullable();
			$table->text("descriptions")->nullable();
			$table->string("year", 255)->nullable();
			$table->timestamps();
            $table->timestamp("deleted_at")->nullable();
            $table->string("created_by", 50)->nullable();
            $table->string("updated_by", 50)->nullable();
            $table->string("deleted_by", 50)->nullable();
            $table->integer("company_id")->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_mcu');
	}

}
