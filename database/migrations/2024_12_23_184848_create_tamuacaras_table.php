<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTamuAcarasTable extends Migration
{
    public function up()
    {
        Schema::create('tamu_acaras', function (Blueprint $table) {
            $table->id();
            $table->string('id_tamu');
            $table->string('kode_acara');
            $table->string('status')->default('tidak hadir');
            $table->timestamps();

            $table->foreign('id_tamu')->references('id_tamu')->on('tamus')->onDelete('cascade');
            $table->foreign('kode_acara')->references('kode_acara')->on('events')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tamu_acaras');
    }
}

