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
        Schema::create('tickets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->uuid('category_id');
            $table->text('description');
            $table->date('solution_deadline')->default(now()->addDays(3));
            $table->uuid('status_id');
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('solved_at')->nullable();


            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
