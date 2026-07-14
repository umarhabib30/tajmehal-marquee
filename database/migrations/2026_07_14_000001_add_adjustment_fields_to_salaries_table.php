<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('salaries', 'adjustment_type')) {
            Schema::table('salaries', function (Blueprint $table) {
                $table->string('adjustment_type')->nullable()->after('deduction_per_absent');
            });
        }

        if (! Schema::hasColumn('salaries', 'adjustment_amount')) {
            Schema::table('salaries', function (Blueprint $table) {
                $table->decimal('adjustment_amount', 10, 2)->default(0)->after('adjustment_type');
            });
        }

        if (! Schema::hasColumn('salaries', 'adjustment_note')) {
            Schema::table('salaries', function (Blueprint $table) {
                $table->text('adjustment_note')->nullable()->after('adjustment_amount');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('salaries', 'adjustment_note')) {
            Schema::table('salaries', function (Blueprint $table) {
                $table->dropColumn('adjustment_note');
            });
        }

        if (Schema::hasColumn('salaries', 'adjustment_amount')) {
            Schema::table('salaries', function (Blueprint $table) {
                $table->dropColumn('adjustment_amount');
            });
        }

        if (Schema::hasColumn('salaries', 'adjustment_type')) {
            Schema::table('salaries', function (Blueprint $table) {
                $table->dropColumn('adjustment_type');
            });
        }
    }
};
