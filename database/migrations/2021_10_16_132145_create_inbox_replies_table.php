<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInboxRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbox_replies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('to');
            $table->string('from');
            $table->string('subject');
            $table->longText('body');
            $table->integer('email_id')->nullable();
            $table->smallInteger('read')->nullable();
            $table->smallInteger('starred')->nullable();
            $table->smallInteger('sent')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('inbox_replies');
    }
}
