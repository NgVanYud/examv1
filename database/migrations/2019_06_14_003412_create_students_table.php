<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
          $table->increments('id');
          $table->uuid('uuid');
          $table->string('first_name');
          $table->string('last_name');
          $table->string('username', 25)->unique();
          $table->string('code');
          $table->string('password');
          $table->timestamp('password_changed_at')->nullable();
          $table->tinyInteger('is_actived')->default(0)->unsigned();
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
        Schema::dropIfExists('students');
    }
}
