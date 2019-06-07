<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalColumnsSubjectTermTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subject_term', function (Blueprint $table) {
          $table->tinyInteger('original_exam_num')->default(1);
          $table->tinyInteger('progression')->default(1);
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
          $table->dropColumn(['original_exam_num', 'progression']);
        });
    }
}
