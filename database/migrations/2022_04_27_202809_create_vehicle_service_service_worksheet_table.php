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
        Schema::create('vehicle_service_service_worksheet', function (Blueprint $table) {
            $table->id();
            $table->uuid('vehicle_service_id');
            $table->uuid('service_worksheet_id');
            $table->timestamps();

            $table->foreign('vehicle_service_id')->references('id')->on('vehicle_services')
                ->cascadeOnDelete();
            $table->foreign('service_worksheet_id')->references('id')->on('service_worksheets')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_service_service_worksheet');
    }
};
