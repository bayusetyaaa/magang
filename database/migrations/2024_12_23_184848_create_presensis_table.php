<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresensisTable extends Migration
{
    public function up()
    {
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->string('nip');
            $table->string('kode_acara');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable(); 
            $table->timestamps();

            $table->foreign('nip')->references('nip')->on('karyawans')->onDelete('cascade');
            $table->foreign('kode_acara')->references('kode_acara')->on('events')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('presensis');
    }
}
