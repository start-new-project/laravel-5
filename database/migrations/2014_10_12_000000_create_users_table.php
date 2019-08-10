<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('username')->unique();
            //images
            $table->integer('avatar')->nullable();
            $table->integer('background')->nullable();
            $table->integer('cover')->nullable();
            //theme
            $table->string('colors')->default('');
            //data
            $table->date('birthday');
            $table->boolean('gender')->default(true);
            $table->string('phone')->default('');
            //security
            $table->boolean('active')->default(false);
            $table->string('role')->default('user');//user,admin,client,student...
            $table->text('access')->default('');
            
            $table->rememberToken();
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
}
