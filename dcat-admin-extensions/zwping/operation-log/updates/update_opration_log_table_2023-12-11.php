<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Update_opration_log_table_2023_12_11 extends Migration
{
    public function getConnection()
    {
        return config('database.connection') ?: config('database.default');
    }

    private const Table = 'admin_operation_log';

    public function up()
    {
        if (Schema::hasTable(static::Table)) {
            Schema::table(static::Table, function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable(static::Table)) {
            Schema::table(static::Table, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
}
