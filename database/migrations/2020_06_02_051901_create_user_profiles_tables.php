<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_profiles', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->json('avatar')->nullable();
            $table->text('bio')->nullable();
            $table->json('socials')->nullable();
            $table->string('contact')->nullable();
            // $table->uuid('customer_id');
            $table->foreignUuid('customer_id')->references('id')->on('users');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('is_active')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
};
