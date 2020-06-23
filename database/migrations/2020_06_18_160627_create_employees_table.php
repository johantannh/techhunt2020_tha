<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('employees')) {
            Schema::drop('employees');
        }
        Schema::create('employees', function (Blueprint $table) {
            $table->string('id');
            $table->string('login');
            $table->string('name');
            $table->unsignedDecimal('salary', 16, 2);
            $table->timestamps();

            $table->unique('id');
            $table->unique('login');
            
            // composite key
            $table->index(['id', 'login']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
