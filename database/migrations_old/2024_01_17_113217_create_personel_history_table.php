<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePersonelHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('personel_histories', function(Blueprint $table) {
			$table->increments('id');
			$table->integer("personel_id")->nullable();
			$table->string("type", 50)->nullable();
			$table->integer("old")->nullable();
			$table->integer("new")->nullable();
			$table->date("start_date")->nullable();
			$table->date("end_date")->nullable();
			$table->text("reason")->nullable();
			$table->string("reference", 255)->nullable();
			$table->integer("mus_approved_by")->nullable();
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
		Schema::drop('personel_histories');
	}

}
