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
        // Categorías
        Schema::create('categories', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('name', 80)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Laboratorios
        Schema::create('laboratories', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('name', 100)->unique();
            $table->string('country', 60)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Presentaciones (caja, frasco, etc.)
        Schema::create('presentations', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('name', 80)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Formas farmacéuticas (tableta, jarabe, ungüento)
        Schema::create('pharmaceutical_forms', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('name', 80)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Unidades de medida (ml, mg, pieza)
        Schema::create('units_of_measure', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('name', 50)->unique();
            $table->string('symbol', 10)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tasas de IVA (0%, 16%, etc.)
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('name', 50);
            $table->decimal('percentage', 5, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Proveedores
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('name', 200);
            $table->string('rfc', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Clientes
        Schema::create('customers', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('name', 200);
            $table->string('rfc', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Doctores
        Schema::create('doctors', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->string('name', 200);
            $table->string('professional_license', 50)->nullable(); // Cédula profesional
            $table->string('university', 150)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('tax_rates');
        Schema::dropIfExists('units_of_measure');
        Schema::dropIfExists('pharmaceutical_forms');
        Schema::dropIfExists('presentations');
        Schema::dropIfExists('laboratories');
        Schema::dropIfExists('categories');
    }
};
