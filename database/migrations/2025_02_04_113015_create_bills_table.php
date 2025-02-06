<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Tabel utama untuk mencatat hutang pribadi
        Schema::create('hutang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_category');
            $table->foreign('id_category')->references('id_category')->on('categories');
            $table->decimal('total_hutang', 15, 2);
            $table->date('tanggal_hutang');
            $table->date('tanggal_selesai');
            $table->enum('status_hutang', ['belum lunas', 'lunas'])->default('belum lunas');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Tabel anak untuk mencatat detail cicilan hutang pribadi
        Schema::create('hutang_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hutang_id')->constrained('hutang')->onDelete('cascade');
            $table->date('tanggal_cicilan');
            $table->decimal('jumlah_cicilan', 15, 2);
            $table->enum('status_cicilan', ['belum bayar', 'sudah bayar'])->default('belum bayar');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hutang_detail');
        Schema::dropIfExists('hutang');
    }
};
