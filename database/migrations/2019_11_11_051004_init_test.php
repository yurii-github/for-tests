<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InitTest extends Migration
{
    const T_FORM = 'form';
    const T_FILE = 'file';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(self::T_FORM, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index()->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create(self::T_FILE, function (Blueprint $table) {
            $table->increments('id');
            $table->binary('data')->nullable(false);
            $table->string('filename');
            $table->string('mime')->nullable(false);
            $table->unsignedInteger('size')->nullable(false);
            $table->unsignedInteger('target_id');
            $table->string('target_type')->nullable(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['target_id', 'target_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::T_FILE);
        Schema::dropIfExists(self::T_FORM);
    }
}
