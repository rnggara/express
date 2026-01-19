<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttEmployeeRegistrationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('att_employee_registration', function(Blueprint $table) {
			$table->increments('id');
            $table->integer("emp_id");
            $table->string("id_card", 255)->nullable();
            $table->integer("workgroup")->nullable();
            $table->integer("leavegroup")->nullable();
            $table->date("date1")->nullable();
            $table->date("date2")->nullable();
            $table->date("date3")->nullable();
            $table->integer("status")->nullable()->default(1);
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
		Schema::drop('att_employee_registration');
	}

}
