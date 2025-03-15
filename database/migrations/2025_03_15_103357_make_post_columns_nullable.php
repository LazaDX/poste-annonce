<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakePostColumnsNullable extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('type')->nullable()->change();
            $table->string('surface')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('whatsapp')->nullable()->change();
            $table->string('payment_method')->nullable()->change();
            $table->string('moteur')->nullable()->change();
            $table->string('nombre_etages')->nullable()->change();
            $table->string('nombre_chambres')->nullable()->change();
            $table->string('nombre_pieces')->nullable()->change();
            $table->string('nombre_couchages')->nullable()->change();
            $table->string('commodites')->nullable()->change();
            $table->string('type_culture')->nullable()->change();
            $table->string('equipements')->nullable()->change();
            $table->string('type_exploitation')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('type')->nullable(false)->change();
            $table->string('surface')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('whatsapp')->nullable(false)->change();
            $table->string('payment_method')->nullable(false)->change();
            $table->string('moteur')->nullable(false)->change();
            $table->string('nombre_etages')->nullable(false)->change();
            $table->string('nombre_chambres')->nullable(false)->change();
            $table->string('nombre_pieces')->nullable(false)->change();
            $table->string('nombre_couchages')->nullable(false)->change();
            $table->string('commodites')->nullable(false)->change();
            $table->string('type_culture')->nullable(false)->change();
            $table->string('equipements')->nullable(false)->change();
            $table->string('type_exploitation')->nullable(false)->change();
        });
    }
}
