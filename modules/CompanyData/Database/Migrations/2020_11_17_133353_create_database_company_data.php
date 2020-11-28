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
            $table->string('company_name');
            $table->string('company_name_acronym');
            $table->string('company_name_en');
            $table->string('address');
            $table->string('phone');
            $table->string('company_type');
            $table->string('company_create_date');
            $table->string('company_status');
            $table->string('representative');
            $table->longText('note');

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
