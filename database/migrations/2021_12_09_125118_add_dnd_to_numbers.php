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
        Schema::table('numbers', function (Blueprint $table) {
            $table->boolean('dnd_calls')->default(false);
            $table->boolean('dnd_voicemail')->default(false);
            $table->boolean('dnd_messages')->default(false);
            $table->boolean('dnd_allow_contacts')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('numbers', function (Blueprint $table) {
            $table->dropColumn('dnd_calls');
            $table->dropColumn('dnd_voicemail');
            $table->dropColumn('dnd_messages');
            $table->dropColumn('dnd_allow_contacts');
        });
    }
};
