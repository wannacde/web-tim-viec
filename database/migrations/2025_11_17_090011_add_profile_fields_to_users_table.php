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
            $table->string('headline')->nullable()->after('email'); // vd: "Lập trình viên PHP"
            $table->json('skills')->nullable()->after('bio'); // Lưu Kỹ năng (dưới dạng JSON)
            $table->json('education')->nullable()->after('skills'); // Lưu Học vấn (dưới dạng JSON)
            $table->json('experience')->nullable()->after('education'); // Lưu Kinh nghiệm (dưới dạng JSON)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
