<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttReasonConditionTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('att_reason_conditions', function(Blueprint $table) {
			$table->increments('id');
            $table->integer("reason_name_id");
            $table->integer("reason_type_id");
            $table->integer("process_sequence");
            $table->integer("reason_sequence");
            $table->integer("report_type_id");
            $table->integer("cut_leave")->default(0);
            $table->integer("ess")->default(0);
            $table->integer("reason_pengganti")->default(0);
            $table->integer("schedule_id")->nullable();
            $table->integer("shift_code")->nullable();
            $table->time("time_in")->nullable();
            $table->time("time_out")->nullable();
            $table->time("late_in")->nullable();
            $table->time("fast_out")->nullable();
            $table->time("overtime")->nullable();
            $table->string("time_in_condition", 255)->nullable();
            $table->string("time_out_condition", 255)->nullable();
            $table->string("late_in_condition", 255)->nullable();
            $table->string("fast_out_condition", 255)->nullable();
            $table->string("overtime_condition", 255)->nullable();
            $table->integer("status")->default(1);
            $table->integer("is_default")->default(0);
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
		Schema::drop('att_reason_conditions');
	}

}
