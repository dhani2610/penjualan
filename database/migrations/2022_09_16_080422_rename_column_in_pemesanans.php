<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnInPemesanans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->renameColumn('name_customer','id_customer');
            $table->renameColumn('nama_produk','id_produk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pemesanans', function (Blueprint $table) {
            $table->renameColumn('id_customer','name_customer');
            $table->renameColumn('id_produk','nama_produk');
        });
    }
}
