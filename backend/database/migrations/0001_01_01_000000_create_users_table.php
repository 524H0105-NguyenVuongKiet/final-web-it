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
        // 1. Bảng Users - Đáp ứng tiêu chí Account Management trang 2
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('display_name'); // Tên hiển thị bắt buộc
            $table->string('email')->unique();
            $table->string('password'); // Sẽ được băm bcrypt
            $table->boolean('is_activated')->default(false); // Trạng thái kích hoạt (0.25đ)
            $table->string('activation_token')->nullable(); // Token gửi qua mail
            $table->timestamps();
        });

        // 2. Bảng Reset Password - Tiêu chí Password Reset (0.25đ)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Bảng Sessions - Duy trì trạng thái đăng nhập
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};