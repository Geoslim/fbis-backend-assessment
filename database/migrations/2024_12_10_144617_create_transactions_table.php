<?php

use App\Models\User;
use App\Models\Wallet;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Wallet::class)->constrained()->cascadeOnDelete();
            $table->decimal('amount');
            $table->string('reference')->unique();
            $table->string('type');
            $table->string('status');
            $table->string('network');
            $table->string('partner');
            $table->string('recipient');
            $table->decimal('commission')->default(0);
            $table->string('description');
            $table->json('response')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
