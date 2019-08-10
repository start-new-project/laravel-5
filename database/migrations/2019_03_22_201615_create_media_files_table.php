<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->default('default');//default/avatar/cover/...
            $table->string('file')->default('image');//image/video/pdf
            $table->string('path')->default('');
            $table->string('filename')->default('');
            $table->boolean('resize')->default(false);
            $table->string('album')->default('');
            $table->boolean('deleted')->default(false);
            $table->integer('by')->nullable(); 
            $table->integer('posted_in')->nullable(); 
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
        Schema::dropIfExists('media_files');
    }
}
