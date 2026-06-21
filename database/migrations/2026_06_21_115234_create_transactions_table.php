<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\TransactionType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('ledger_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->date('date');

            $table->string('trx_type')
                ->default(TransactionType::Debit->value);

            $table->text('notes')->nullable();

            $table->foreignId('payment_method_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('ref_no')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

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
