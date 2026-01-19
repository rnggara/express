<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttEmployeeRegistrationHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('att_employee_registration_histories', function(Blueprint $table) {
			$table->increments('id');
            $table->integer("type");
            $table->integer("reg_id");
            $table->date("date");
            $table->text("new_data")->nullable();
            $table->text("old_data")->nullable();
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
		Schema::drop('att_employee_registration_histories');
	}

}
