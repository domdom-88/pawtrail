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
    Schema::create('visits', function (Blueprint $table) {
        $table->id();
        $table->foreignId('spot_id')->constrained()->cascadeOnDelete();
        $table->foreignId('dog_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->text('notes')->nullable();
        $table->timestamp('visited_at')->useCurrent();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('visits');
}
};
