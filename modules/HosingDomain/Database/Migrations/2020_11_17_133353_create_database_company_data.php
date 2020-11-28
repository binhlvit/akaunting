<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatabaseCompanyData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('company_name')->nullable();
            $table->string('company_name_acronym')->nullable();
            $table->string('company_name_en')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('company_type')->nullable();
            $table->string('company_create_date')->nullable();
            $table->string('company_status')->nullable();
            $table->string('representative')->nullable();

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
        Schema::dropIfExists('');
    }
}
