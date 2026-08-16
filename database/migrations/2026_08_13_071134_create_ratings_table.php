<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('product_id')->constrained('products');
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'product_id']);
            $table->index(['product_id', 'created_at']);
        });

        // SQLite test database không hỗ trợ ADD CONSTRAINT bằng ALTER TABLE.
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement(
                'ALTER TABLE `ratings`
                 ADD CONSTRAINT `ratings_rating_check`
                 CHECK (`rating` BETWEEN 1 AND 5)'
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
