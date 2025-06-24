<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('t_lowongan', function (Blueprint $table) {
            $table->id('lowongan_id');
            $table->unsignedBigInteger('user_id'); //ambil dr user (role perusahaan)
            $table->unsignedBigInteger('posisi_id'); //ambil dr m_posisi
            $table->string('lowongan_kode', 10)->unique();
            $table->string('lowongan_nama', 100);
            $table->text('lowongan_lokasi')->nullable();
            $table->text('lowongan_deskripsi')->nullable();
            $table->text('lowongan_kualifikasi')->nullable(); //kek bisa ngonfig, iso excel dll
            $table->dateTime('lowongan_expired')->nullable();

            $table->timestamps();

            // foreign key
            $table->foreign('user_id')->references('user_id')->on('m_user');
            $table->foreign('posisi_id')->references('posisi_id')->on('m_posisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_lowongan', function (Blueprint $table) {
            //
        });
    }
};
