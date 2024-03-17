<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->enum('level',['Admin','Petugas']);
            $table->rememberToken();
            $table->timestamps();
        });
        DB::table('users')->insert(array(
            [   
                'name'     => 'pera',
                'username' => 'pera',
                'password' => bcrypt('111111'),
                'level'    => 'Admin'
            ],
            [
                'name'     => 'patika',
                'username' => 'patika',
                'password' => bcrypt('111111'),
                'level'    => 'Petugas'],
        ));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
