<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreatePersonelProfileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('personel_profiles', function(Blueprint $table) {
			$table->increments('id');
			$table->integer("user_id")->nullable();
			$table->string("identity_type", 255)->nullable();
			$table->string("identity_number", 255)->nullable();
			$table->string("identity_file", 255)->nullable();
			$table->string("citizenship", 255)->nullable();
			$table->integer("marital_status")->nullable();
			$table->integer("religion")->nullable();
			$table->integer("gender")->nullable();
			$table->integer("blood_type")->nullable();
			$table->integer("height")->nullable();
			$table->integer("weight")->nullable();
			$table->text("identity_address")->nullable();
			$table->text("identity_zip_code")->nullable();
			$table->text("identity_country")->nullable();
			$table->text("identity_province")->nullable();
			$table->text("identity_city")->nullable();
			$table->text("resident_address")->nullable();
			$table->text("resident_zip_code")->nullable();
			$table->text("resident_country")->nullable();
			$table->text("resident_province")->nullable();
			$table->text("resident_city")->nullable();
			$table->text("npwp")->nullable();
			$table->text("npwp_file")->nullable();
			$table->text("bpjskes")->nullable();
			$table->text("bpjskes_file")->nullable();
			$table->text("bpjstk")->nullable();
			$table->text("bpjstk_file")->nullable();
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
		Schema::drop('personel_profiles');
	}

}
