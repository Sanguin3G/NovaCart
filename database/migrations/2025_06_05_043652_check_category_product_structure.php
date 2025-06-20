<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if categories table exists and has the correct structure
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                // Add any missing columns
                if (!Schema::hasColumn('categories', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('parent_id');
                }
            });
        } else {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->foreignId('parent_id')
                    ->nullable()
                    ->constrained('categories')
                    ->nullOnDelete();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // Check if products table exists and has the correct structure
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                // Add any missing columns
                if (!Schema::hasColumn('products', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('image_url');
                }

                // Ensure foreign key exists
                if (!Schema::hasColumn('products', 'category_id')) {
                    $table->foreignId('category_id')
                        ->constrained()
                        ->cascadeOnDelete();
                }
            });
        } else {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10);
                $table->integer('stock')->default(0);
                $table->foreignId('category_id')
                    ->constrained()
                    ->cascadeOnDelete();
                $table->string('image_url', 2048)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop tables in case they contain data
        // Just remove the columns we might have added
        if (Schema::hasColumn('categories', 'is_active')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }

        if (Schema::hasColumn('products', 'is_active')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
};
