<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'payment_status')) {
                $table->string('payment_status')->nullable()->after('status');
            }

            if (!Schema::hasColumn('users', 'activated_at')) {
                $table->timestamp('activated_at')->nullable()->after('payment_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'payment_status')) {
                $table->dropColumn('payment_status');
            }

            if (Schema::hasColumn('users', 'activated_at')) {
                $table->dropColumn('activated_at');
            }
        });
    }
};



