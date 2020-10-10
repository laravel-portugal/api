<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('trusted')->default(false);
            $table->timestampTz('email_verified_at')->nullable();

            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }
}
