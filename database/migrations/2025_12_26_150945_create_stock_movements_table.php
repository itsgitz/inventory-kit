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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // FOREIGN KEY `product_id`
            $table->foreignUlid('product_id')
                ->constrained()
                ->onDelete('restrict')
                ->index();

            // FOREIGN KEY `user_id`
            $table->foreignUlid('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('restrict')
                ->index();

            $table->bigInteger('quantity');
            $table->text('reason');
            $table->enum('type', ['IN', 'OUT'])->index();
            $table->text('notes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
