<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRandomStringsTable extends Migration
{
    public function up()
    {
        Schema::create('random_strings', function (Blueprint $table) {
            $table->id();
            $table->string('random_string', 20)->unique();
            $table->timestamps();
            $table->index('random_string'); // Index for random_string
        });
    }

    public function down()
    {
        Schema::dropIfExists('random_strings');
    }
}
