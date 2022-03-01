<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('number_id')->constrained();
            $table->foreignId('service_account_id')->constrained();
            $table->unsignedBigInteger('contact_id')->nullable()->onDelete('null');
            $table->string('from', 15)->index();
            $table->string('to', 15)->index();
            $table->text('body')->nullable();
            $table->string('error_code', 20)->nullable();
            $table->text('error_message')->nullable();
            $table->string('direction', 15);
            $table->string('status', 15);
            $table->unsignedSmallInteger('num_media')->default(0);
            $table->text('media')->nullable();
            $table->string('external_identity')->index()->nullable();
            $table->dateTime('external_date_created')->nullable();
            $table->dateTime('external_date_updated')->nullable();
            $table->boolean('read')->default(false);
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
