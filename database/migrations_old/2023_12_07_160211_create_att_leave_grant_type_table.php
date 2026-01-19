<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttLeaveGrantTypeTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('att_leave_grant_types', function(Blueprint $table) {
			$table->increments('id');
            $table->string("grant_leave_id", 255)->nullable();
            $table->string("grant_leave_name", 255)->nullable();
            $table->integer("status")->nullable()->default(1);
            $table->integer("is_default")->nullable()->default(0);
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
		Schema::drop('att_leave_grant_types');
	}

}
