<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsDoneColumnSubjectTermTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subject_term', function(Blueprint $table) {
          $table->tinyInteger('is_done')->default(0);
          $table->tinyInteger('is_configed')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subject_term', function (Blueprint $table) {
          $table->dropColumn(['is_done', 'is_configed']);
        });
    }
}
