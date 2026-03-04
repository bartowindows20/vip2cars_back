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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('names', 200);
            $table->string('paternal_surname', 200);
            $table->string('maternal_surname')->nullable();
            $table->string('document_type', 20);
            $table->string('document_number', 30);
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();
            $table->unique(['document_type', 'document_number']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
