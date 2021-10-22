<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InboxEmails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbox_emails', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('to');
            $table->string('from');
            $table->longText('subject');
            $table->longText('body');
            $table->integer('email_uid')->nullable();
            $table->smallInteger('read')->nullable();
            $table->smallInteger('starred')->nullable();
            $table->smallInteger('sent')->nullable();
            $table->smallInteger('bookmarked')->nullable();
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
        //
    }
}
