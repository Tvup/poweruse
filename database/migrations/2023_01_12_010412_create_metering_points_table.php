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
        /**
         * customer-and-third-party-api-for-datahub-eloverblik-data-description-6382010_19_0.pdf
         * CUSTOMER AND THIRD PARTY API FOR DATAHUB (ELOVERBLIK) - DATA DESCRIPTION
         * Above documentation laid foundation for data definition.
         * Field names are in snake_case but in the documentation it's PascalCase
         * Not all fields are included from the documentation since it seems like not all fields are included in the
         * api we're using
         * Length of strings etc. are retrieved from:
         * RSM-Guide - rsm-guide-edi-transaktioner-for-det-danske-elmarked-ver-5-7-7.pdf
         *
         * However our own needs when retrieving these data supersedes when deciding data properties
         */
        Schema::create('metering_points', function (Blueprint $table) {
            $table->id();
            $table->string('metering_point_id',18);
            $table->string('type_of_mp', 3);
            $table->string('settlement_method', 3);
            $table->string('meter_number', 15);
            $table->string('consumer_c_v_r', 10)->nullable();
            $table->string('data_access_c_v_r', 10)->nullable();
            $table->date('consumer_start_date')->nullable(); //Originates from ISO 8601 - YYYY-MM-DDTHH:MMZ //Always UTC-0 //Here Europe/Copenhagen (UTC+1/UTC+2) (time irrelevant - only use date)
            $table->string('meter_reading_occurrence', 5);
            $table->string('balance_supplier_name'); //Couldn't find anything on limitation on this one :(
            //FROM HERE: document/DK_NotifyMasterDataConsumer/ebIX_BusinessDataType_0p1pA.xsd -TextType_000114
            $table->string('street_code',4);//But in RSM-Guide only 4 characters
            $table->string('street_name',40);//But in RSM-Guide only 40 characters
            $table->string('building_number',6);//But in RSM-Guide only 6 characters (actually a restriction also: If DK, only 4)
            $table->string('floor_id',4)->nullable();//But in RSM-Guide only 4 characters
            $table->string('room_id',4)->nullable();//But in RSM-Guide only 4 characters
            $table->string('city_name',25);//But in RSM-Guide only 25 characters
            $table->string('city_sub_division_name',34)->nullable();//But in RSM-Guide only 34 characters
            $table->string('municipality_code',3); //But in RSM-Guide only 3 characters
            $table->string('location_description',132)->nullable();//But in RSM-Guide only 60 characters
            $table->string('first_consumer_party_name',132)->nullable(); //Also defined in RSM-Guide
            $table->string('second_consumer_party_name',132)->nullable();//Also defined in RSM-Guide
            //END: TextType_000114
            $table->boolean('hasRelation');
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
        Schema::dropIfExists('metering_points');
    }
};
