<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('roles', function (Blueprint $table) {

            $table->increments('id');
            $table->string('name');
            $table->text('description');
            $table->timestamps();

        });

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('role_id')->default(\App\Role::STUDENT);
            $table->foreign('role_id')->references('id')->on('roles');
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('slug');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('picture')->nullable();

            //  Cashier columns son agregadas  automáticamente
             $table->string('stripe_id')->nullable();
             $table->string('card_brand')->nullable();
             $table->string('card_last_four')->nullable();
             $table->timestamp('trial_ends_at')->nullable(); //  dos funciones diferentes timestamp y timestamps

            $table->rememberToken();
            $table->timestamps();
        });

        //  cashier crea automáticamente la tabla subscriptions una vez ha sido instalado

        Schema::create('subscriptions', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->string('stripe_id');
            $table->string('stripe_plan');
            $table->integer('quantity');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

        });


        Schema::create('user_social_accounts', function (Blueprint $table) {


            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('provider'); //  nombre red social con la que nos logueamos
            $table->string('provider_uid'); //  nuestro id de la red con la que nos identificamos

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
        Schema::dropIfExists('user_social_accounts');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
        
    }
    
}
