<?php

use App\Scraper\Banks;
use App\Scraper\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scrapes', function (Blueprint $table) {
            $table->string('hash')->primary();
            $table->enum('bank', array_map(function (Banks $banks) {
                return $banks->value;
            }, Banks::cases()))->index();
            $table->integer('cashback')->nullable();
            $table->text('raw');
            $table->string('title');
            $table->string('url');
            $table->string('image_url')->nullable();
            $table->text('limitation')->nullable();
            $table->text('condition')->nullable();

            $table->enum('category', array_map(function (Category $category) {
                return $category->value;
            }, Category::cases()))->nullable();

            $table->string('franchise')->nullable();
            $table->string('city')->nullable();
            $table->unsignedBigInteger('created_at')->nullable();
            $table->unsignedBigInteger('time_end')->nullable();
            $table->unsignedBigInteger('time_start')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrapes');
    }
};
