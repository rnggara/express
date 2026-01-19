<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttWorkgroupTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('att_workgroups', function(Blueprint $table) {
			$table->increments('id');
            $table->string("workgroup_id", 255)->nullable();
            $table->string("workgroup_name", 255)->nullable();
            $table->date("start_date")->nullable();
            $table->integer("patern")->nullable();
            $table->integer("sequence")->nullable();
            $table->integer("replace_holiday_flag")->nullable();
            $table->integer('status')->default(1)->nullable();
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
		Schema::drop('att_workgroups');
	}

}
