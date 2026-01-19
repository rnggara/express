<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateExpressVendorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('express_vendor', function(Blueprint $table) {
			$table->increments('id');
            $table->string("nama", 255)->nullable();
            $table->text("logo_path")->nullable();
            $table->decimal("harga", 20, 2)->nullable();
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
		Schema::drop('express_vendor');
	}

}
