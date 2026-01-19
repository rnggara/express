<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateKjkCrmAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('kjk_crm_addresses', function(Blueprint $table) {
			$table->increments('id');
            $table->integer("target_id")->nullable();
            $table->string("type", 255)->nullable();
            $table->string("title", 255)->nullable();
            $table->string("address", 255)->nullable();
            $table->string("postal_code", 255)->nullable();
            $table->string("country", 255)->nullable();
            $table->string("province", 255)->nullable();
            $table->string("city", 255)->nullable();
            $table->string("subdistrict", 255)->nullable();
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
		Schema::drop('kjk_crm_addresses');
	}

}
