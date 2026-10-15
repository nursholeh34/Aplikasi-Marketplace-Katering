<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable()->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('merchant_id')->constrained()->cascadeOnDelete();
            $table->date('delivery_date');
            $table->string('delivery_address');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('total')->default(0);
            $table->enum('status', ['pending', 'confirmed', 'delivered', 'cancelled'])
                ->default('pending')->index();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_id')->nullable()->constrained()->nullOnDelete();
            $table->string('menu_name');
            $table->unsignedInteger('price');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('subtotal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
