<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePersonelOffenceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('personel_offence', function(Blueprint $table) {
			$table->increments('id');
			$table->integer("emp_id")->nullable();
			$table->integer("offence_reason")->nullable();
			$table->integer("given_by")->nullable();
			$table->date("start_date")->nullable();
			$table->date("end_date")->nullable();
			$table->text("remarks")->nullable();
			$table->string("reference", 255)->nullable();
			$table->text("lampiran")->nullable();
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
		Schema::drop('personel_offence');
	}

}
