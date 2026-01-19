<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateExpressBookOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('express_book_order', function(Blueprint $table) {
			$table->increments('id');
            $table->integer("book_id")->nullable();
            $table->string("sender_as", 255)->nullable();
            $table->string("sender_name", 255)->nullable();
            $table->string("sender_nik", 255)->nullable();
            $table->string("sender_npwp", 255)->nullable();
            $table->string("sender_address", 255)->nullable();
            $table->string("sender_phone", 255)->nullable();
            $table->string("sender_email", 255)->nullable();
            $table->string("recipient_as", 255)->nullable();
            $table->string("recipient_name", 255)->nullable();
            $table->string("recipient_address", 255)->nullable();
            $table->string("recipient_phone", 255)->nullable();
            $table->string("recipient_email", 255)->nullable();
            $table->string("recipient_passport", 255)->nullable();
            $table->string("recipient_clearance", 255)->nullable();
            $table->string("recipient_taxid", 255)->nullable();
            $table->string("zip", 255)->nullable();
            $table->string("alasan_expor", 255)->nullable();
            $table->string("isi_kiriman", 255)->nullable();
            $table->string("items", 255)->nullable();
            $table->decimal("total_harga_usd", 20, 2)->nullable();
            $table->decimal("asuransi", 20, 2)->nullable();
            $table->integer("jumlah_fumigasi")->nullable();
            $table->decimal("fumigasi", 20, 2)->nullable();
            $table->decimal("promo", 20, 2)->nullable();
            $table->string("promocode", 255)->nullable();
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
		Schema::drop('express_book_order');
	}

}
