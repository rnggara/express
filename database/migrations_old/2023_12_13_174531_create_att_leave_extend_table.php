<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttLeaveExtendTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('att_leave_extends', function(Blueprint $table) {
			$table->increments('id');
            $table->integer("emp_id")->nullable();
            $table->string("type", 255)->nullable();
            $table->integer("months")->nullable();
            $table->string("periode", 255)->nullable();
            $table->timestamp("approved_at")->nullable();
            $table->timestamp("rejected_at")->nullable();
            $table->string("approved_by", 50)->nullable();
            $table->string("rejected_by", 50)->nullable();
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
		Schema::drop('att_leave_extends');
	}

}
