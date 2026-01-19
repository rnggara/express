<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttLeafeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('att_leaves', function(Blueprint $table) {
			$table->increments('id');
            $table->string("leave_group_id", 255)->nullable();
            $table->string("leave_group_name", 255)->nullable();
            $table->integer("show_by_join_date")->nullable();
            $table->integer("show_by_cut_off")->nullable();
            $table->integer("annual_leave_expired")->nullable();
            $table->integer("annual_over_right")->nullable();
            $table->integer("annual_expired_change")->nullable();
            $table->integer("annual_pay_end_periode")->nullable();
            $table->text("annual_total_leaves")->nullable();
            $table->integer("long_expired")->nullable();
            $table->integer("long_pay_end_periode")->nullable();
            $table->text("long_total_leaves")->nullable();
            $table->text("special_total_leaves")->nullable();
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
		Schema::drop('att_leaves');
	}

}
