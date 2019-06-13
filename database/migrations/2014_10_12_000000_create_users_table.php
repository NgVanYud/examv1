<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username', 25);
            $table->string('email')->unique();
            $table->string('code');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamp('password_changed_at')->nullable();
            $table->tinyInteger('is_actived')->default(0)->unsigned();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('managers');
    }
}
