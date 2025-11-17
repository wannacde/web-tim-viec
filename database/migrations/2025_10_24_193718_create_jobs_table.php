<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->text('benefits')->nullable();
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();
            $table->enum('salary_type', ['hourly', 'daily', 'weekly', 'monthly'])->default('hourly');
            $table->enum('work_type', ['online', 'offline', 'hybrid'])->default('offline');
            $table->json('work_schedule')->nullable(); // ['morning', 'afternoon', 'evening', 'weekend']
            $table->enum('experience_level', ['no_experience', 'under_1_year', '1_3_years', 'over_3_years'])->default('no_experience');
            $table->integer('positions')->default(1);
            $table->enum('status', ['draft', 'active', 'paused', 'expired', 'closed'])->default('active');
            $table->date('deadline')->nullable();
            $table->integer('views')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_urgent')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};