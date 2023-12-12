<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('table_matches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('team_id')->unique()->constrained('teams');
            $table->bigInteger('matches');
            $table->bigInteger('wins');
            $table->bigInteger('loses');
            $table->bigInteger('draw');
            $table->bigInteger('points');
            $table->bigInteger('goals_team');
            $table->bigInteger('goals_conceded');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_matches');
    }
};
