<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttWorkgroupPaternTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('att_workgroup_paterns', function(Blueprint $table) {
			$table->increments('id');
            $table->string("patern_id", 255)->nullable();
            $table->string("patern_name", 255)->nullable();
            $table->integer("type")->nullable();
            $table->text("sequences")->nullable();
            $table->integer("status")->default(1)->nullable();
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
		Schema::drop('att_workgroup_paterns');
	}

}
