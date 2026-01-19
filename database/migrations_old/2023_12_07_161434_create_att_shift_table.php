<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttShiftTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('att_shifts', function(Blueprint $table) {
			$table->increments('id');
            $table->string("shift_id", 255)->nullable();
            $table->string("shift_name", 255)->nullable();
            $table->integer("day_code")->nullable();
            $table->text("shift_color")->nullable();
            $table->time("schedule_in")->nullable();
            $table->time("schedule_out")->nullable();
            $table->time("break_in")->nullable();
            $table->time("break_out")->nullable();
            $table->time("overtime_in")->nullable();
            $table->time("overtime_out")->nullable();
            $table->integer("add_break_in")->nullable();
            $table->integer("automatic_overtime")->nullable();
            $table->integer("status")->nullable()->default(1);
            $table->integer("is_default")->nullable()->default(0);
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
		Schema::drop('att_shifts');
	}

}
