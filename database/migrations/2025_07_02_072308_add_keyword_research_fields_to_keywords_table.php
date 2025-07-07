<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('keywords', function (Blueprint $table) {
            $table->decimal('cpc', 8, 2)->nullable()->after('difficulty');
            $table->string('competition', 20)->nullable()->after('cpc');
            $table->string('intent', 50)->nullable()->after('competition');
            $table->string('trend', 20)->nullable()->after('intent');
            $table->string('category', 50)->nullable()->after('trend');
        });
    }

    public function down()
    {
        Schema::table('keywords', function (Blueprint $table) {
            $table->dropColumn(['cpc', 'competition', 'intent', 'trend', 'category']);
        });
    }
};