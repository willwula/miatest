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
            $table->unsignedInteger('role')->default(\App\Models\User::ROLE_NORMAL)->after('id'); //2 定義於User model
        });
        // unsignedInteger() 欄位屬性、default() 該欄位預設值、after('id) 此欄位新增於id欄之後
        // migrate 完記得 rollback 看看有沒有正確運作，避免大患
        // $fillable 加上role
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
