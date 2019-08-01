<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusColumnToSubjectTermTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subject_term', function (Blueprint $table) {
            $table->tinyInteger('status')->default(\App\Models\SubjectTerm::WAITING);
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
            $table->dropColumn(['status']);
        });
    }
}
