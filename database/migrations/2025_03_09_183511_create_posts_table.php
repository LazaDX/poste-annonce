<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('title_price');
            $table->string('type');
            $table->string('surface');
            $table->string('city');
            $table->string('phone');
            $table->string('whatsapp');
            $table->string('payment_method');
            $table->string('moteur');
            $table->string('nombre_etages');
            $table->string('nombre_chambres');
            $table->string('nombre_pieces');
            $table->string('nombre_couchages');
            $table->string('commodites');
            $table->string('type_culture');
            $table->string('equipements');
            $table->string('type_exploitation');
            $table->text('description');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
