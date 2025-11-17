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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->enum('role', ['student', 'employer', 'admin'])->default('student');
            // Bắt đầu thêm các cột cho Employer
            $table->string('company_name')->nullable()->after('role');
            $table->string('company_logo')->nullable()->after('company_name');
            $table->text('company_description')->nullable()->after('company_logo');
            $table->string('company_website')->nullable()->after('company_description');
            $table->string('company_address')->nullable()->after('company_website');
            $table->boolean('is_verified')->default(false)->after('company_address');
            // Kết thúc thêm cột
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
