<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttOvertimeRecordTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('att_overtime_record', function(Blueprint $table) {
			$table->increments('id');
			$table->integer("emp_id")->nullable();
			$table->date("overtime_date")->nullable();
			$table->integer("reason_id")->nullable();
			$table->string("overtime_type", 255)->nullable();
			$table->time("overtime_time")->nullable();
			$table->integer("add_break")->nullable();
			$table->text("breaks")->nullable();
			$table->string("paid", 255)->nullable();
			$table->integer("days")->nullable();
			$table->integer("departement")->nullable();
			$table->string("reference", 255)->nullable();
			$table->text("file_name")->nullable();
			$table->text("file_address")->nullable();
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
		Schema::drop('att_overtime_record');
	}

}
