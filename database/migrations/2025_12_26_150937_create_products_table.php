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
        Schema::create('products', function (Blueprint $table) {
            $table->ulid('id')->primary();

            // FOREIGN KEY `category_id` -> categories.id
            $table->foreignUlid('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // FOREIGN KEY `supplier_id` -> `suppliers.id`
            $table->foreignUlid('supplier_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('name')->index();
            $table->string('code')->unique();
            $table->text('description');
            $table->decimal('unit_price');
            $table->bigInteger('current_stock')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
