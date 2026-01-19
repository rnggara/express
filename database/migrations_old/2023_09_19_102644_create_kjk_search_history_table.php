<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateKjkSearchHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kjk_search_histories', function(Blueprint $table) {
			$table->increments('id');
            $table->integer("user_id");
            $table->text("lokasi")->nullable();
            $table->text("spec")->nullable();
            $table->text("job_type")->nullable();
            $table->text("salary")->nullable();
            $table->text("edu")->nullable();
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
		Schema::drop('kjk_search_histories');
	}

}
