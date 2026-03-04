<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vtu_plans', function (Blueprint $table) {
            $table->string('api_code')->nullable()->after('provider_id')->comment('Payscribe plan code');
        });
    }

    public function down()
    {
        Schema::table('vtu_plans', function (Blueprint $table) {
            $table->dropColumn('api_code');
        });
    }
};