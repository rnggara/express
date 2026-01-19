<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateExpressRefundRequestTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('express_refund_requests', function(Blueprint $table) {
			$table->increments('id');
            $table->integer("order_id");
            $table->decimal('amount', 20, 2)->nullable();
            $table->string("bank_name", 255)->nullable();
            $table->string("no_rekening", 255)->nullable();
            $table->string("account_name", 255)->nullable();
            $table->timestamp('transfer_at')->nullable();
            $table->integer('transfer_by')->nullable();
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
		Schema::drop('express_refund_requests');
	}

}
