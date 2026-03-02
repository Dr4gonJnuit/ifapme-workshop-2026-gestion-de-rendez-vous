<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rdv', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
        });
    }

    public function down()
    {
        Schema::table('rdv', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time']);
            $table->timestamp('date')->nullable();
        });
    }
};