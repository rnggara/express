<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateAttHolidayTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('att_holidays', function(Blueprint $table) {
			$table->increments('id');
            $table->string("category_id")->nullable();
            $table->string("name")->nullable();
            $table->date("holiday_date");
            $table->integer("send_notification")->default(0);
            $table->integer("status")->default(1);
            $table->integer("is_default")->default(0);
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
		Schema::drop('att_holidays');
	}

}
