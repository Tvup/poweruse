<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('datahub_price_lists', function (Blueprint $table) {
            $table->index(['Note', 'GLN_Number', 'Description', 'ValidFrom', 'ValidTo'], 'common_retrieval_index');
        });
        //Tested with explain ANALYZE select * from poweruse.`datahub_price_lists` where `note` = 'Transmissions nettarif' and `gln_number` = '5790000432752' and `description` = 'Netafgiften, for både forbrugere og producenter, dækker omkostninger til drift og vedligehold af det overordnede elnet (132/150 og 400 kv nettet) og drift og vedligehold af udlandsforbindelserne.' and NOT (ValidFrom > '2023-01-10 05:00:00' OR (IF(ValidTo is null,'2030-01-01',ValidTo) < '2023-01-01' )) and `datahub_price_lists`.`deleted_at` is null
        //Result said that index common_retrieval_index where used.
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('datahub_price_lists', function (Blueprint $table) {
            $table->dropIndex('common_retrieval_index');
        });
    }
};
