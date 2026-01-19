<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateKjkCompanyReviewTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kjk_company_reviews', function(Blueprint $table) {
			$table->increments('id');
            $table->integer("company_id")->nullable();
			$table->integer("user_id")->nullable();
			$table->integer("overall_rating")->nullable();
			$table->integer("is_recommended")->nullable();
			$table->integer("salary_avg")->nullable();
			$table->integer("is_employee")->nullable();
			$table->string("position", 255)->nullable();
			$table->integer("job_type")->nullable();
            $table->string("title", 255)->nullable();
            $table->text("pros")->nullable();
            $table->text("cons")->nullable();
            $table->integer("rating_1")->nullable();
            $table->integer("rating_2")->nullable();
            $table->integer("rating_3")->nullable();
            $table->integer("rating_4")->nullable();
            $table->integer("rating_5")->nullable();
            $table->integer("rating_6")->nullable();
            $table->integer("rating_7")->nullable();
            $table->integer("rating_8")->nullable();
            $table->integer("stress_level")->nullable();
			$table->timestamps();
            $table->timestamp("deleted_at")->nullable();
            $table->string("created_by", 50)->nullable();
            $table->string("updated_by", 50)->nullable();
            $table->string("deleted_by", 50)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('kjk_company_reviews');
	}

}
