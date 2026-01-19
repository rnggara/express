<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttMachineNameTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('att_machine_names', function(Blueprint $table) {
			$table->increments('id');
			$table->integer("program_id")->nullable();
            $table->string("machine_id", 255)->nullable();
            $table->string("machine_name", 255)->nullable();
            $table->integer("in_code")->nullable();
            $table->integer("out_code")->nullable();
            $table->text("absensi_code")->nullable();
            $table->text("date_format")->nullable();
            $table->text("time_format")->nullable();
            $table->text("id_card")->nullable();
            $table->text("error_pathfile")->nullable();
            $table->integer('status')->nullable()->default(1);
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
		Schema::drop('att_machine_names');
	}

}
