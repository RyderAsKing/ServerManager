<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHostnameAndIpv4ToVpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vps', function (Blueprint $table) {
            //
            $table->string('hostname', 32)->nullable();
            $table->string('ipv4', 32)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vps', function (Blueprint $table) {
            //
            $table->dropColumn('hostname');
            $table->dropColumn('ipv4');
        });
    }
}
