<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharges', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('subscriber_id')->nullable()->unsigned();
            $table->string('amount');
            $table->string('validity');
            $table->string('due_date');
            $table->enum('is_notification_active', ['yes','no'])->default('no');
            $table->enum('notification_status', ['pending','complete'])->default('pending');
            $table->timestamps();
            $table->foreign('subscriber_id')->references('id')->on('subscribers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recharges');
    }
};
