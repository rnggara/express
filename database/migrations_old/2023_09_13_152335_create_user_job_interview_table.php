<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateUserJobInterviewTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_job_interviews', function(Blueprint $table) {
			$table->increments('id');
            $table->integer("job_app_id");
            $table->integer("int_date");
            $table->time("int_start");
            $table->time("int_end");
            $table->integer("int_type");
            $table->text("int_link");
            $table->text("int_location");
            $table->text("int_test");
            $table->text("int_descriptions");
            $table->integer("int_officer");
            $table->text("int_notes");
            $table->text("int_file_address");
            $table->text("int_file_name");
            $table->integer("int_review_by");
            $table->timestamp("int_review_at");
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
		Schema::drop('user_job_interview');
	}

}
