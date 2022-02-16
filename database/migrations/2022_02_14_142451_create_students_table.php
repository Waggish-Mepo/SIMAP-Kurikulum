<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->integer('nuptk')->nullable();
            $table->integer('nik')->nullable();
            $table->integer('no_induk_yayasan')->nullable();
            $table->integer('no_ukg')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('nama_ibu_kandung')->nullable();
            $table->string('nama_ayah_kandung')->nullable();
            $table->string('alamat_jalan')->nullable();
            $table->unsignedSmallInteger('rt')->nullable();
            $table->unsignedSmallInteger('rw')->nullable();
            $table->string('dusun')->nullable();
            $table->string('kelurahan_desa')->nullable();
            $table->string('kecamatan')->nullable();
            $table->integer('kode_pos')->nullable();
            $table->string('lintang')->nullable();
            $table->string('bujur')->nullable();
            $table->string('no_kartu_keluarga')->nullable();
            $table->string('agama')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->integer('npwp')->nullable();
            $table->string('nama_wajib_pajak')->nullable();
            $table->string('status_perkawinan')->nullable();
            $table->integer('nip_suami_istri')->nullable();
            $table->string('pekerjaan_suami_istri')->nullable();
            $table->boolean('status_kepegawaian')->nullable();
            $table->string('jenis_ptk')->nullable();
            $table->string('sk_pengangkatan')->nullable();
            $table->string('tmt_pengangkatan')->nullable();
            $table->string('lembaga_pengangkat')->nullable();
            $table->string('sk_cpns')->nullable();
            $table->string('tmt_cpns')->nullable();
            $table->string('pangkat_golongan')->nullable();
            $table->string('sumber_gaji')->nullable();
            $table->integer('kartu_pegawai')->nullable();
            $table->integer('kartu_suami_istri')->nullable();
            $table->string('lisensi_kepala_sekolah')->nullable();
            $table->integer('no_registrasi_nuksi')->nullable();
            $table->string('keahlian_labolatorium')->nullable();
            $table->boolean('mampu_menangani_kebutuhan_khusus')->nullable();
            $table->string('keahlian_braile')->nullable();
            $table->string('keahlian_bahasa_isyarat')->nullable();
            $table->integer('no_telp_rumah')->nullable();
            $table->integer('no_hp')->nullable();
            $table->integer('no_surat_tugas')->nullable();
            $table->date('tgl_surat_tugas')->nullable();
            $table->string('tmt_tugas')->nullable();
            $table->boolean('status_sekolah_induk')->nullable();
            $table->boolean('jenis_sertifikasi')->nullable();
            $table->integer('no_sertifikasi')->nullable();
            $table->integer('tahun_sertifikasi')->nullable();
            $table->string('bidang_studi_sertifikasi')->nullable();
            $table->string('nrg_sertifikasi')->nullable();
            $table->integer('no_peserta_sertifikasi')->nullable();
            $table->string('jenjang_pendidikan')->nullable();
            $table->string('gelar_akademi')->nullable();
            $table->string('satuan_pendidikan')->nullable();
            $table->integer('tahun_masuk')->nullable();
            $table->integer('tahun_keluar')->nullable();
            $table->integer('no_induk')->nullable();
            $table->integer('ipk')->nullable();
            $table->jsonb('anak')->nullable();
            $table->boolean('status')->nullable();
            $table->string('jenjang_pendidikan_anak')->nullable();
            $table->integer('nisn')->nullable();
            $table->boolean('jk');
            $table->string('tempat_lahir_anak')->nullable();
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
        Schema::dropIfExists('students');
    }
}