<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttLeaveRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('att_leave_requests', function(Blueprint $table) {
			$table->increments('id');
            $table->integer("emp_id")->nullable();
            $table->integer("reason_type")->nullable();
            $table->string("leave_used", 255)->nullable();
            $table->date("start_date")->nullable();
            $table->date("end_date")->nullable();
            $table->string("ref_num")->nullable();
            $table->string("notes")->nullable();
            $table->text("file_name")->nullable();
            $table->text("file_url")->nullable();
            $table->timestamp("approved_at")->nullable();
            $table->integer("approved_by")->nullable();
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
		Schema::drop('att_leave_requests');
	}

}
