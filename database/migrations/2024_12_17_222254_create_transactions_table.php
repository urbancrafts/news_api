<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_wallet_id')->nullable()->constrained('wallets')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('reciepient_wallet_id')->nullable()->constrained('wallets')->onDelete('cascade')->onUpdate('cascade');
            $table->decimal('amount',10,2)->default(0.00);
            $table->enum('status', ['Pending', 'Failed', 'Sent', 'Reversed', 'Confirmed', 'Rejected'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
