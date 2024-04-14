<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scrape_payment_types', function (Blueprint $table) {
            $table->id();
            $table->string("payment_type");
            $table->string("hash");

            $table
                ->foreign("payment_type")
                ->references("name")
                ->on("payment_types")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table
                ->foreign("hash")
                ->references("hash")
                ->on("scrapes")
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scrape_payment_types');
    }
};
