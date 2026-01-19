<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePersonelResignTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('personel_resign', function(Blueprint $table) {
			$table->increments('id');
			$table->integer("emp_id")->nullable();
			$table->date("resign_date")->nullable();
			$table->integer("resign_type")->nullable();
			$table->integer("resign_reason")->nullable();
			$table->integer("blacklist_flag")->nullable();
			$table->text("remarks")->nullable();
			$table->integer("must_approved_by")->nullable();
			$table->date("approved_at")->nullable();
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
		Schema::drop('personel_resign');
	}

}
