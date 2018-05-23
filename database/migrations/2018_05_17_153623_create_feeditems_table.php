<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeditemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feeditems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('channel');
            $table->string('title');
            $table->string('link')->nullable();
            $table->string('description')->default('');
            $table->text('content');
            // - We will store the api_key id of the creator of the item, so we
            //   can know which client has created an item.
            // - This can allow us to retrieve the user id, but we directly
            //   store the user id so it is faster to retrieve which items send
            //   to a client
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('api_client_id')->nullable();
            $table->timestamps();

            $table->foreign('api_client_id')->references('id')->on('api_clients');
            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feeditems');
    }
}
