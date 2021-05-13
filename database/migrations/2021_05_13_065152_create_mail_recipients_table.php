<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_recipients', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('mail_id')->constrained();
            $table->foreignId('recipient_id')->constrained();

            $table->string('email');
            $table->string('status')->default('unknown');
            $table->timestamp('resolved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_recipients');
    }
}
