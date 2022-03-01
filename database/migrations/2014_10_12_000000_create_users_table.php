<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->string('time_zone')->default('America/New_York');

            $table->bigInteger('gotify_user_id')->unsigned()->nullable();
            $table->string('gotify_user_name')->nullable();
            $table->text('gotify_user_pass')->nullable();
            $table->bigInteger('gotify_client_id')->unsigned()->nullable();
            $table->text('gotify_client_token')->nullable();
            $table->bigInteger('gotify_app_id')->unsigned()->nullable();
            $table->text('gotify_app_token')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
