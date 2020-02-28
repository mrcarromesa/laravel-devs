<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MudarCampoGithubUsernameUnique extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devs', function (Blueprint $table) {
            $table->unique('github_username', 'unique_github_username');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devs', function (Blueprint $table) {
            $table->dropUnique('unique_github_username');
            //
        });
    }
}
