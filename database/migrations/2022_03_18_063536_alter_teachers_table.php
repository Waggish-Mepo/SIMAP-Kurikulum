<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('nik');
            $table->dropColumn('no_induk_yayasan');
            $table->dropColumn('no_ukg');
            $table->dropColumn('tempat_lahir');
            $table->dropColumn('tgl_lahir');
            $table->dropColumn('nama_ibu_kandung');
            $table->dropColumn('nama_ayah_kandung');
            $table->dropColumn('alamat_jalan');
            $table->dropColumn('rt');
            $table->dropColumn('rw');
            $table->dropColumn('dusun');
            $table->dropColumn('kelurahan_desa');
            $table->dropColumn('kecamatan');
            $table->dropColumn('kode_pos');
            $table->dropColumn('lintang');
            $table->dropColumn('bujur');
            $table->dropColumn('no_kartu_keluarga');
            $table->dropColumn('agama');
            $table->dropColumn('kewarganegaraan');
            $table->dropColumn('npwp');
            $table->dropColumn('nama_wajib_pajak');
            $table->dropColumn('status_perkawinan');
            $table->dropColumn('nip_suami_istri');
            $table->dropColumn('pekerjaan_suami_istri');
            $table->dropColumn('status_kepegawaian');
            $table->dropColumn('jenis_ptk');
            $table->dropColumn('sk_pengangkatan');
            $table->dropColumn('tmt_pengangkatan');
            $table->dropColumn('lembaga_pengangkat');
            $table->dropColumn('sk_cpns');
            $table->dropColumn('tmt_cpns');
            $table->dropColumn('pangkat_golongan');
            $table->dropColumn('sumber_gaji');
            $table->dropColumn('kartu_pegawai');
            $table->dropColumn('kartu_suami_istri');
            $table->dropColumn('lisensi_kepala_sekolah');
            $table->dropColumn('no_registrasi_nuksi');
            $table->dropColumn('keahlian_laboratorium');
            $table->dropColumn('mampu_menagani_kebutuhan_khusus');
            $table->dropColumn('keahlian_braille');
            $table->dropColumn('keahlian_bahasa_isyarat');
            $table->dropColumn('no_telp_rumah');
            $table->dropColumn('no_hp');
            $table->dropColumn('no_surat_tugas');
            $table->dropColumn('tgl_surat_tugas');
            $table->dropColumn('tmt_tugas');
            $table->dropColumn('status_sekolah_induk');
            $table->dropColumn('jenis_sertifikasi');
            $table->dropColumn('no_sertifikasi');
            $table->dropColumn('tahun_sertifikasi');
            $table->dropColumn('bidang_studi_sertifikasi');
            $table->dropColumn('nrg_sertifikasi');
            $table->dropColumn('no_peserta_sertifikasi');
            $table->dropColumn('jenjang_pendidikan');
            $table->dropColumn('satuan_pendidikan');
            $table->dropColumn('tahun_masuk');
            $table->dropColumn('tahun_keluar');
            $table->dropColumn('no_induk');
            $table->dropColumn('ipk');
            $table->dropColumn('anak');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->integer('nik')->nullable();
            $table->string('no_induk_yayasan')->nullable();
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
            $table->smallInteger('kode_pos')->nullable();
            $table->string('lintang')->nullable();
            $table->string('bujur')->nullable();
            $table->integer('no_kartu_keluarga')->nullable();
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
            $table->string('keahlian_laboratorium')->nullable();
            $table->string('mampu_menagani_kebutuhan_khusus')->nullable();
            $table->string('keahlian_braille')->nullable();
            $table->string('keahlian_bahasa_isyarat')->nullable();
            $table->integer('no_telp_rumah')->nullable();
            $table->integer('no_hp')->nullable();
            $table->integer('no_surat_tugas')->nullable();
            $table->date('tgl_surat_tugas')->nullable();
            $table->string('tmt_tugas')->nullable();
            $table->boolean('status_sekolah_induk')->nullable();
            $table->string('jenis_sertifikasi')->nullable();
            $table->integer('no_sertifikasi')->nullable();
            $table->integer('tahun_sertifikasi')->nullable();
            $table->string('bidang_studi_sertifikasi')->nullable();
            $table->string('nrg_sertifikasi')->nullable();
            $table->integer('no_peserta_sertifikasi')->nullable();
            $table->string('jenjang_pendidikan')->nullable();
            $table->string('satuan_pendidikan')->nullable();
            $table->integer('tahun_masuk')->nullable();
            $table->integer('tahun_keluar')->nullable();
            $table->integer('no_induk')->nullable();
            $table->float('ipk')->nullable();
            $table->jsonb('anak')->nullable();
        });
    }
}
