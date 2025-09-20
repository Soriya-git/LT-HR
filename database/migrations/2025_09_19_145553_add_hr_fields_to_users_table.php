<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // your convention: always have {table}_txid
            $table->string('users_txid')->nullable()->after('id');

            // HR profile fields
            $table->string('phone')->nullable()->after('email');
            $table->string('department')->nullable()->after('phone');
            $table->string('position')->nullable()->after('department');
            $table->date('hire_date')->nullable()->after('position');
            $table->decimal('salary_monthly', 12, 2)->nullable()->after('hire_date');
            $table->string('employment_status')->default('active')->after('salary_monthly');

            // reporting line (self reference)
            $table->unsignedBigInteger('manager_id')->nullable()->after('employment_status');
            $table->foreign('manager_id')->references('id')->on('users')->nullOnDelete();

            // indexes that are commonly useful
            $table->index(['department']);
            $table->index(['employment_status']);
            $table->index(['manager_id']);
            $table->index(['users_txid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
            $table->dropIndex(['users_department_index']);
            $table->dropIndex(['users_employment_status_index']);
            $table->dropIndex(['users_manager_id_index']);
            $table->dropIndex(['users_users_txid_index']);

            $table->dropColumn([
                'users_txid','phone','department','position','hire_date',
                'salary_monthly','employment_status','manager_id'
            ]);
        });
    }
};
