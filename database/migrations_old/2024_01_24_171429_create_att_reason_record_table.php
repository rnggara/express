<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttReasonRecordTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('att_reason_records', function(Blueprint $table) {
			$table->increments('id');
			$table->integer("emp_id")->nullable();
			$table->date("att_date")->nullable();
			$table->time("timin")->nullable();
			$table->time("timout")->nullable();
			$table->text("reason_values")->nullable();
			$table->integer("reason_id")->nullable();
			$table->integer("day_code")->nullable();
			$table->integer("shift_id")->nullable();
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
		Schema::drop('att_reason_records');
	}

}
