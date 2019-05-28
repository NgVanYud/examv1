<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDefaultValueForIsActivedColumnChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chapters', function(Blueprint $table) {
          $table->unsignedSmallInteger('is_actived')->default(\App\Models\Chapter::ACTIVE_STATUS)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('chapters', function(Blueprint $table) {
        $table->unsignedSmallInteger('is_actived')->change();
      });
    }
}
