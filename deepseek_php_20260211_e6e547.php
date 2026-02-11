<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone')->unique();
            $table->string('password');
            $table->enum('role', ['user', 'admin', 'doctor', 'agency'])->default('user');
            $table->enum('status', ['pending', 'active', 'blocked'])->default('pending');
            $table->tinyInteger('account_level')->default(0); // 0=normal,1=1month,3=3month,6=6month,12=12month
            $table->timestamp('verified_at')->nullable();
            $table->string('locale', 2)->default('fa');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};