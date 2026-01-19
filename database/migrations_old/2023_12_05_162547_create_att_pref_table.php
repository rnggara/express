<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttPrefTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('att_pref', function(Blueprint $table) {
			$table->increments('id');
            $table->integer("dolp")->nullable();
            $table->integer("dacp")->nullable();
            $table->integer("cop")->nullable();
            $table->integer("cor")->nullable();
            $table->date("wsp")->nullable();
            $table->date("tap")->nullable();
            $table->date("cdm")->nullable();
            $table->integer("grace_in")->nullable();
            $table->integer("grace_out")->nullable();
            $table->integer("early_in")->nullable();
            $table->integer("early_out")->nullable();
            $table->integer("late_in")->nullable();
            $table->integer("late_out")->nullable();
            $table->integer("ovt_rounded_value")->nullable();
            $table->integer("ovt_round")->nullable();
            $table->integer("ovt_join_round")->nullable();
            $table->integer("ovt_split_calculate")->nullable();
            $table->integer("ovt_late_in")->nullable();
            $table->integer("ovt_permission")->nullable();
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
		Schema::drop('att_pref');
	}

}
