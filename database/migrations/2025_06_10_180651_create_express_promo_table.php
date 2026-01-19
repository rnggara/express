<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateExpressPromoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('express_promo', function(Blueprint $table) {
			$table->increments('id');
            $table->string("code", 255);
            $table->string("description", 255)->nullable();
            $table->date("start_date")->nullable();
            $table->date("end_date")->nullable();
            $table->integer("source")->default(0)->nullable();
            $table->text("formula")->nullable();
            $table->decimal("amount", 20, 2)->nullable();
            $table->decimal("amount_limit", 20, 2)->nullable();
            $table->string('target')->nullable();
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
		Schema::drop('express_promo');
	}

}
