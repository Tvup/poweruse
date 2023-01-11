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
        DB::table('datahub_price_lists')->truncate();
        Schema::table('datahub_price_lists', function (Blueprint $table) {
            $table->date('ValidFrom')->change();
            $table->date('ValidTo')->nullable()->change();
            $table->decimal('Price1', 12, 6)->change();
            $table->decimal('Price2', 12, 6)->nullable()->change();
            $table->decimal('Price3', 12, 6)->nullable()->change();
            $table->decimal('Price4', 12, 6)->nullable()->change();
            $table->decimal('Price5', 12, 6)->nullable()->change();
            $table->decimal('Price6', 12, 6)->nullable()->change();
            $table->decimal('Price7', 12, 6)->nullable()->change();
            $table->decimal('Price8', 12, 6)->nullable()->change();
            $table->decimal('Price9', 12, 6)->nullable()->change();
            $table->decimal('Price10', 12, 6)->nullable()->change();
            $table->decimal('Price11', 12, 6)->nullable()->change();
            $table->decimal('Price12', 12, 6)->nullable()->change();
            $table->decimal('Price13', 12, 6)->nullable()->change();
            $table->decimal('Price14', 12, 6)->nullable()->change();
            $table->decimal('Price15', 12, 6)->nullable()->change();
            $table->decimal('Price16', 12, 6)->nullable()->change();
            $table->decimal('Price17', 12, 6)->nullable()->change();
            $table->decimal('Price18', 12, 6)->nullable()->change();
            $table->decimal('Price19', 12, 6)->nullable()->change();
            $table->decimal('Price20', 12, 6)->nullable()->change();
            $table->decimal('Price21', 12, 6)->nullable()->change();
            $table->decimal('Price22', 12, 6)->nullable()->change();
            $table->decimal('Price23', 12, 6)->nullable()->change();
            $table->decimal('Price24', 12, 6)->nullable()->change();
            $table->dropPrimary();
            $table->primary(array('ChargeType', 'ChargeTypeCode', 'GLN_number', 'ValidFrom', 'Note'), 'primary_price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('datahub_price_lists')->truncate();
        Schema::table('datahub_price_lists', function (Blueprint $table) {
            $table->string('ValidFrom', 19)->change();
            $table->string('ValidTo', 19)->nullable()->change();
            $table->float('Price1')->change();
            $table->float('Price2')->nullable()->change();
            $table->float('Price3')->nullable()->change();
            $table->float('Price4')->nullable()->change();
            $table->float('Price5')->nullable()->change();
            $table->float('Price6')->nullable()->change();
            $table->float('Price7')->nullable()->change();
            $table->float('Price8')->nullable()->change();
            $table->float('Price9')->nullable()->change();
            $table->float('Price10')->nullable()->change();
            $table->float('Price11')->nullable()->change();
            $table->float('Price12')->nullable()->change();
            $table->float('Price13')->nullable()->change();
            $table->float('Price14')->nullable()->change();
            $table->float('Price15')->nullable()->change();
            $table->float('Price16')->nullable()->change();
            $table->float('Price17')->nullable()->change();
            $table->float('Price18')->nullable()->change();
            $table->float('Price19')->nullable()->change();
            $table->float('Price20')->nullable()->change();
            $table->float('Price21')->nullable()->change();
            $table->float('Price22')->nullable()->change();
            $table->float('Price23')->nullable()->change();
            $table->float('Price24')->nullable()->change();
            $table->dropPrimary();
            $table->primary(array('ChargeType', 'ChargeTypeCode', 'Note', 'ValidFrom'), 'primary_price');
        });
    }
};
